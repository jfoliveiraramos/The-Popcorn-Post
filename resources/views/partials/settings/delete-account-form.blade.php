<section id='delete-account' class='delete-account flex flex-col mt-8'>
    <header class="font-title text-black mb-5">
        <h1 class=" text-3xl font-bold ">Delete Account</h1>
        <h2>Delete your account permanently. Be aware that this action cannot be undone.</h2>
    </header>
    <button type="button" id="delete-own-account-button"
        class="self-center bg-red text-white rounded py-2 px-3 mt-5"">Delete Account</button>
    <dialog id="delete-own-account-dialog" class="dialog w-4/6 sm:w-1/2 xl:w-2/5 2xl:w-1/5 bg-white rounded-xl shadow-std py-7 px-10">
        <form method="POST" action="/members/{{ $member->username }}/settings"
            class="flex flex-col items-center">
            {{ method_field('delete') }}
            {{ csrf_field() }}
            <h3 class="mb-5 text-center">
                Are you sure you want to 
                <strong class="">delete</strong>
                 your account? Remember, this action 
                 <strong class="">cannot be undone</strong>!
            </h3>
                <div class="account-password flex flex-col w-full lg:w-2/3 gap-3">
                    <div class="flex flex-col">
                        <label for="password" class="required">Enter your password:</label>
                        <input type="password" name="password" id="password" required class="rounded border border-red pl-3 py-1">
                        @if ($errors->has('password'))
                            <span>
                                <i class="bi bi-x-circle text-xxs"></i>
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                        </div>
                    <div class="flex flex-col">
                        <label for="confirm-password" class="required">Confirm your password:</label>
                        <input type="password" name="confirm-password" id="confirm-password" required class="rounded border border-red pl-3 py-1">
                        @if ($errors->has('confirm-password'))
                            <span>
                                <i class="bi bi-x-circle text-xxs"></i>
                                {{ $errors->first('confirm-password') }}
                            </span>
                        @endif
                        </div>
                </div>
                <div class="dialog-actions flex flex-row justify-between w-full mt-10">
                    <button type="submit" id="confirm-delete-own-account-button" class="bg-red text-white rounded-md px-2 py-1"
                        onclick="disableSubmit(this)">Delete
                        Account</button>
                    <button type="button" class="bg-white text-red border border-red rounded-md px-2 py-1" id="cancel-delete-own-account-button">Cancel</button>
                </div>
        </form>
    </dialog>
    <script>
        @if ($errors->has('password') || $errors->has('confirm-password'))
            document.getElementById('delete-own-account-dialog').showModal();
        @endif
    </script>
</section>
