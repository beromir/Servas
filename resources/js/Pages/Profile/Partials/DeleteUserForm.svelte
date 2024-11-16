<script>
    import JetActionSection from '@/Jetstream/ActionSection.svelte'
    import JetDialogModal from '@/Jetstream/DialogModal.svelte'
    import JetDangerButton from '@/Jetstream/DangerButton.svelte'
    import JetInput from '@/Jetstream/Input.svelte'
    import JetInputError from '@/Jetstream/InputError.svelte'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.svelte'
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";

    let confirmingUserDeletion = false;
    let passwordInput;

    let form = useForm({
        password: '',
    });

    function showConfirmUserDeletionModal() {
        confirmingUserDeletion = true;

        setTimeout(() => passwordInput.focus(), 250)
    }

    function deleteUser() {
        $form.delete(route('current-user.destroy'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => passwordInput.focus(),
            onFinish: () => $form.reset(),
        });
    }

    function closeModal() {
        confirmingUserDeletion = false

        $form.reset();
    }
</script>

<JetActionSection>
    <svelte:fragment slot="title">
        Delete Account
    </svelte:fragment>
    >

    <svelte:fragment slot="description">
        Permanently delete your account.
    </svelte:fragment>
    >

    <svelte:fragment slot="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting
            your account, please download any data or information that you wish to retain.
        </div>

        <div class="mt-5">
            <JetDangerButton on:clicked={showConfirmUserDeletionModal}>
                Delete Account
            </JetDangerButton>
        </div>

        <!-- Delete Account Confirmation Modal -->
        <JetDialogModal show={confirmingUserDeletion} on:close={closeModal}>
            <svelte:fragment slot="title">
                Delete Account
            </svelte:fragment>

            <svelte:fragment slot="content">
                Are you sure you want to delete your account? Once your account is deleted, all of its resources and
                data will be permanently deleted. Please enter your password to confirm you would like to permanently
                delete your account.

                <div class="mt-4">
                    <JetInput type="password" class="mt-1 block w-3/4" placeholder="Password"
                              bind:this={passwordInput} bind:value={$form.password}/>

                    <JetInputError message={$form.errors.password} class="mt-2"/>
                </div>
            </svelte:fragment>

            <svelte:fragment slot="footer">
                <JetSecondaryButton on:clicked={closeModal}>
                    Cancel
                </JetSecondaryButton>

                <JetDangerButton class="ml-3" on:clicked={deleteUser}>
                    Delete Account
                </JetDangerButton>
            </svelte:fragment>
        </JetDialogModal>
    </svelte:fragment>
</JetActionSection>
