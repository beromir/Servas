<script>
    import JetActionSection from '@/Jetstream/ActionSection.svelte'
    import JetDialogModal from '@/Jetstream/DialogModal.svelte'
    import JetDangerButton from '@/Jetstream/DangerButton.svelte'
    import JetInputError from '@/Jetstream/InputError.svelte'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.svelte'
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";
    import Input from "@/Components/Input.svelte";

    let confirmingUserDeletion = $state(false);

    let form = useForm({
        password: '',
    });

    function showConfirmUserDeletionModal() {
        confirmingUserDeletion = true;
    }

    function deleteUser() {
        $form.delete(route('current-user.destroy'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onFinish: () => $form.reset(),
        });
    }

    function closeModal() {
        confirmingUserDeletion = false

        $form.reset();
    }
</script>

<JetActionSection>
    {#snippet title()}
        Delete Account
    {/snippet}

    {#snippet description()}
        Permanently delete your account.
    {/snippet}

    {#snippet content()}
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting
            your account, please download any data or information that you wish to retain.
        </div>

        <div class="mt-5">
            <JetDangerButton clicked={showConfirmUserDeletionModal}>
                Delete Account
            </JetDangerButton>
        </div>

        <!-- Delete Account Confirmation Modal -->
        <JetDialogModal show={confirmingUserDeletion} close={closeModal}>
            {#snippet title()}
                Delete Account
            {/snippet}

            {#snippet content()}
                Are you sure you want to delete your account? Once your account is deleted, all of its resources and
                data will be permanently deleted. Please enter your password to confirm you would like to permanently
                delete your account.

                <div class="mt-4">
                    <Input type="password" placeholder="Password" bind:value={$form.password} class="mt-1"/>
                    <JetInputError message={$form.errors.password} class="mt-2"/>
                </div>
            {/snippet}

            {#snippet footer()}
                <JetSecondaryButton clicked={closeModal}>
                    Cancel
                </JetSecondaryButton>

                <JetDangerButton class="ml-3" clicked={deleteUser}>
                    Delete Account
                </JetDangerButton>
            {/snippet}
        </JetDialogModal>
    {/snippet}
</JetActionSection>
