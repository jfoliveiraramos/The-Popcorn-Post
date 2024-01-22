<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\ContentItem;
use App\Models\Article;
use App\Models\Comment;
use App\Models\ProfileImage;
use App\Models\Appeal;
use App\Events\NotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function createMember(Request $request) {
        $account = Auth::user();
        $this->authorize('create', $account);
        
        $request->validate([
            'firstName' => 'required|alpha|max:250',
            'lastName' => 'required|alpha|max:250',
            'username' => 'required|string|max:250|unique:member',
            'email' => 'required|email|max:250|unique:member',
            'password' => 'required|min:8',
            'confirmPassword' => 'required|min:8|same:password'
        ]);

        $password = $request->password;

        if (!preg_match('/[a-z]/', $password)) {
            return redirect()->back()->withInput()->withErrors([
                'password' => 'Password must contain at least one lowercase letter.'
            ]);
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return redirect()->back()->withInput()->withErrors([
                'password' => 'Password must contain at least one uppercase letter.'
            ]);
        }

        if (!preg_match('/[0-9]/', $password)) {
            return redirect()->back()->withInput()->withErrors([
                'password' => 'Password must contain at least one digit.'
            ]);
        }

        if (!preg_match('/[\'^£$%&*()}{@#~!?><>,|=_+¬-]/', $password)) {
            return redirect()->back()->withInput()->withErrors([
                'password' => 'Password must contain at least one special character.'
            ]);
        }

        Member::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

       
        return redirect()->route('member', ['username' => $request->username])->withSuccess('Account created successfully!');
    }
    
    public function showCreateMember() {
        $account = Auth::user();
        $this->authorize('manage', $account);

        return view('pages.create-member');
    }
    
    public function showProfile(String $username)
    {
        
        $member = Member::where('username', $username)->firstOrFail();
        
        $this->authorize('view', [Member::class, $member]);
        
        return view('pages.member', [
            'member' => $member,
            'title' => $member->name() . "'s Profile - The Popcorn Post"
        ]);
    }

    public function showEditProfile(String $username)
    {
        $member = Member::where('username', $username)->firstOrFail();
        
        $this->authorize('update', $member);
        
        return view('pages.edit-member-profile', [
            'member' => $member,
            'title' => $member->name() . "'s Profile - The Popcorn Post"
        ]);
    }

    public function updateProfile(Request $request, String $username)
    {
        $member = Member::where('username', $username)->firstOrFail();
        
        $this->authorize('update', $member);
        
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = $member->username . '.png';
            
            if ($member->profile_image_id != 0) {
                $image->move(public_path('/images/profile'), $filename);
            }

            else {
                $profileImage = new ProfileImage();
                $profileImage->file_name = $filename;
                $profileImage->save();
                $member->profile_image_id = $profileImage->id;
                $member->save();
                $image->move(public_path('/images/profile'), $filename);
            }

        } 
        
        if($request->bio != null) {
            $request->validate([
                'bio' => 'required|string|max:250',
            ]);
            $member->biography = $request->bio;   
            $member->save();
        }

        return redirect()->route('member', ['username' => $username])->withSuccess('Profile updated!');
    }

    public function showSettings(String $username)
    {
        $member = Member::where('username', $username)->firstOrFail();
        
        $this->authorize('update', $member);

        return view('pages.edit-member-settings', [
            'member' => $member,
            'title' => $member->name() . "'s Settings - The Popcorn Post"
        ]);
    }

    public function promote(Request $request, String $username)
    {
        $authed = Auth::user();
        $member = Member::where('username', $username)->firstOrFail();
        
        $this->authorize('promote', $member);

        if ($request->password == null) {
            return redirect()->back()->withErrors(['snackbar' => 'Password is required!']);
        }

        if ($request->confirm_password == null) {
            return redirect()->back()->withErrors(['snackbar' => 'Confirm password is required!']);
        }

        if ($request->password != $request->confirm_password) {
            return redirect()->back()->withErrors(['snackbar' => 'Passwords do not match!']);
        }

        if (!Hash::check($request->password, $authed->password)) {
            return redirect()->back()->withErrors(['snackbar' => 'Password is incorrect!']);
        }

        $member->is_admin = $request->is_admin;
        
        $member->save();

        return redirect()->route('member', ['username' => $username])->withSuccess('Member promoted successfully!');
    }

    public function updateSettings(Request $request, String $username)
    {
        $member = Member::where('username', $username)->firstOrFail();

        $this->authorize('update', $member);
        
        if($request->first_name != null) {
            $request->validate([
                'first_name' => 'required|alpha|max:250',
            ]);
            $member->first_name = $request->first_name;
        }

        if($request->last_name != null) {
            $request->validate([
                'last_name' => 'required|alpha|max:250',
            ]);
            $member->last_name = $request->last_name;
        }

        if($request->username != null && $request->username != $member->username) {
            $request->validate([
                'username' => 'required|string|max:250|unique:member',
            ]);
            $member->username = $request->username;
        }

        if($request->email != null && $request->email != $member->email) {
            $request->validate([
                'email' => 'required|email|max:250|unique:member',
            ]);
            $member->email = $request->email;
        }

        if ($request->old_password != null || $request->new_password != null || $request->confirm_new_password != null) {

            if (!preg_match('/[a-z]/', $request->new_password)) {
                return redirect()->back()->withInput()->withErrors([
                    'new_password' => 'Password must contain at least one lowercase letter.'
                ]);
            }
    
            if (!preg_match('/[A-Z]/', $request->new_password)) {
                return redirect()->back()->withInput()->withErrors([
                    'new_password' => 'Password must contain at least one uppercase letter.'
                ]);
            }

            if (!preg_match('/[0-9]/', $request->new_password)) {
                return redirect()->back()->withErrors([
                    'new_password' => 'Password must contain at least one digit.'
                ]);
            }

            if (!preg_match('/[\'^£$%&*()}{@#~!?><>,|=_+¬-]/', $request->new_password)) {
                return redirect()->back()->withErrors([
                    'new_password' => 'Password must contain at least one special character.'
                ]);
            }
            
            $request->validate([
                'new_password' => 'required|min:8',
                'confirm_new_password' => 'required|same:new_password',
            ]);

            if (Hash::check($request->old_password, $member->password)) {
                $member->password = Hash::make($request->new_password);
            }

            else {
                return redirect()->back()->withErrors(['old_password' => 'Old password is incorrect!']);
            }
        }

        

        $member->save();

        return redirect()->route('member', ['username' => $member->username])->withSuccess('Settings updated!');
    }

    public function block(String $username) {
        
        $member = Member::where('username', $username)->firstOrFail();

        $this->authorize('block', $member);   

        $member->is_blocked = true;
        
        $member->save();

        event(new NotificationEvent("/members/" . $username, $member->id, "Your account has been blocked!"));

        return redirect()->route('member', [$username])->withSuccess('Member account blocked successfully!');
    }

    public function unblock(String $username) {

        $member = Member::where('username', $username)->firstOrFail();

        $this->authorize('unblock', $member);

        $member->is_blocked = false;
        
        $member->save();

        return redirect()->route('member', [$username])->withSuccess('Member account unblocked successfully!');
    }

    public function delete(String $username) {

        $member = Member::where('username', $username)->firstOrFail();

        $this->authorize('delete', $member);

        $member->is_deleted = true;
        
        $member->save();

        return redirect()->route('administration')->withSuccess('Member account deleted successfully!');
    }

    public function deleteOwnAccount(Request $request, String $username) {
        $member = Member::where('username', $username)->firstOrFail();
        $this->authorize('deleteOwnAccount', $member);

        $request->validate([
            'password' => 'required',
            'confirm-password' => 'required|same:password'
        ]);

        if (!Hash::check($request->password, $member->password)) {
            return redirect()->back()->withErrors(['password' => 'Password is incorrect!']);
        }
        
        $member->is_deleted = true;
        
        $member->save();

        Auth::logout();

        return redirect()->route('home')->withSuccess('Account deleted successfully!');
    }

    public function showAdministration() {
        $account = Auth::user();
        $this->authorize('manage', $account);

        $appeals = Appeal::getAllAppeals();

        return view('pages.administration', [
            'appeals' => $appeals,
            'title' => 'Administration - The Popcorn Post'
        ]);
    }

    public function getMembers(Request $request) {
        $account = Auth::user();
        $this->authorize('manage', $account);

        $page = $request->has('page') ? $request->input('page') : 1;
        $items_per_page = 5;

        $items = Member::where('is_deleted', false)->orderBy('username', 'asc')->paginate($items_per_page, ['*'], 'page', $page);

        $isLastPage = $items->currentPage() >= $items->lastPage();

        $html = view('partials.administration.list-members', [
            'members' => $items,
        ])->render();
        
        return response()->json([
            'html' => $html,
            'isLastPage' => $isLastPage
        ]);
    }

    public function follow(String $username) {
        $account = Auth::user();
        $member = Member::where('username', $username)->firstOrFail();
        $this->authorize('follow', $member);

        $authenticatedMember = Member::where('id', $account->id)->firstOrFail();
        $authenticatedMember->following()->attach($member->id);

        event(new NotificationEvent("/members/" . $username, $member->id, "$account->username is now following you!"));

        return redirect()->route('member', ['username' => $username])->withSuccess('You are now following ' . $member->name() . '!');
    }

    public function unfollow(String $username) {
        $account = Auth::user();
        $authenticatedMember = Member::where('id', $account->id)->firstOrFail();
        $member = Member::where('username', $username)->firstOrFail();
        $this->authorize('unfollow', $member);

        $member->followers()->detach($authenticatedMember->id);

        return redirect()->route('member', ['username' => $username])->withSuccess('You are no longer following ' . $member->name() . '!');
    }

    public function getArticles(Request $request, String $username) {
        $member = Member::where('username', $username)->firstOrFail();
        $this->authorize('view', [Member::class, $member]);

        $page = $request->has('page') ? $request->input('page') : 1;
        $member = Member::where('username', $username)->first();
        $items_per_page = 2;

        $items = Article::join('content_item', 'article.id', '=', 'content_item.id')
        ->where('is_deleted', false)->where('author_id', $member->id)->orderBy('date_time', 'desc')->paginate($items_per_page, ['*'], 'page', $page);

        $isLastPage = $items->currentPage() >= $items->lastPage();

        $html = view('partials.profile.articles-profile', [
            'articles' => $items,
        ])->render();
        
        return response()->json([
            'html' => $html,
            'isLastPage' => $isLastPage
        ]);
    }

    public function getComments(Request $request, String $username) {
        $member = Member::where('username', $username)->firstOrFail();
        $this->authorize('view', [Member::class, $member]);

        $page = $request->has('page') ? $request->input('page') : 1;
        $member = Member::where('username', $username)->first();
        $items_per_page = 2;

        $items = Comment::join('content_item', 'comment.id', '=', 'content_item.id')
        ->where('is_deleted', false)->where('author_id', $member->id)->orderBy('date_time', 'desc')->paginate($items_per_page, ['*'], 'page', $page);

        $isLastPage = $items->currentPage() >= $items->lastPage();

        $html = view('partials.profile.comments-profile', [
            'comments' => $items,
        ])->render();
        
        return response()->json([
            'html' => $html,
            'isLastPage' => $isLastPage
        ]);
    }
}
