<div id='edit-profile' class='edit-profile'>
    <form method='POST' action='/members/{{ $member->username }}' enctype="multipart/form-data"
        class="bg-white rounded-xl shadow-std p-5 flex flex-col">
        {{ method_field('patch') }}
        {{ csrf_field() }}
        <div class='edit-profile-image flex flex-row items-end mb-5' id='edit-profile-image'>
            @php $profileImage = $member->profile_image @endphp
            <img class="w-24 h-24 object-cover rounded-full border border-brown" src='/images/profile/{{ $profileImage->file_name }}'
                class='profile-image' alt='Profile Image' width=10%>
            <div class='edit-profile-image-label-container flex flex-col ml-2'>
                <label for='profile_image'
                    class='font-title text-brown text-base hover:underline hover:cursor-pointer focus:underline  edit-profile-image-label'>Change
                    profile
                    image</label>
                <input type='file' id='profile_image' name='profile_image' class="text-xs text-brown ">
                @if ($errors->has('profile_image'))
                    <span class="error">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->first('profile_image') }}
                    </span>
                @endif
            </div>
        </div>
        <div class='edit-profile-text field flex flex-col mb-5'>
            <label class="text-xl font-title font-bold mb-1" for="bio">Biography:</label>
            <textarea class="border rounded-xl border-brown p-3 resize-none" rows=3 id="bio" name="bio">{{ $member->biography }}</textarea>
            @if ($errors->has('bio'))
                <span class="error">
                    <i class="bi bi-x-circle text-xxs"></i>
                    {{ $errors->first('bio') }}
                </span>
            @endif
        </div>
        <button type="submit" class="bg-red text-white rounded px-2 py-1 w-fit self-center"
            onclick="disableSubmit(this)">Save Changes</button>
    </form>
</div>
