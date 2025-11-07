<script>
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import {useForm} from "@inertiajs/svelte";
    import DangerButton from "@/Jetstream/DangerButton.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import Modal from "@/Components/Modals/Modal.svelte";
    import SuccessMessage from "@/Jetstream/SuccessMessage.svelte";

    let deleteOptions = [
        'links',
        'groups',
        'tags',
    ];

    let userDataForm = useForm({
        deleteOptions: [],
    })

    let modal = $state();

    let showSuccessMessage = $state(false);

    function requestConfirmation() {
        modal.show();
        showSuccessMessage = false;
    }

    function deleteUserData() {
        $userDataForm.post(route('delete-user-data'), {
            preserveScroll: true,
            onSuccess: () => {
                $userDataForm.reset();
                modal.close();
                showSuccessMessage = true;
            }
        });
    }
</script>

<JetFormSection submitted={requestConfirmation}>
    {#snippet title()}
        Delete Data
    {/snippet}

    {#snippet description()}
        Delete your account data.
    {/snippet}

    {#snippet form()}
        <div class="col-span-3 space-y-6">
            <div>
                {#each deleteOptions as option}
                    <label class="flex items-center">
                        <input type="checkbox" value={option} bind:group={$userDataForm.deleteOptions}
                               class="rounded-sm border-gray-300 text-primary-600 shadow-xs dark:bg-gray-800 dark:border-gray-600"/>
                        <span class="ml-2 text-sm text-gray-600 capitalize dark:text-gray-300">{option}</span>
                    </label>
                {/each}
            </div>
        </div>
    {/snippet}

    {#snippet actions()}
        <SuccessMessage bind:show={showSuccessMessage} message="The selected data was deleted."/>
        <DangerButton type="submit" disabled={!$userDataForm.deleteOptions.length}>
            Delete
        </DangerButton>
    {/snippet}
</JetFormSection>

<Modal bind:this={modal} title="Delete Data">
    <p class="text-sm text-gray-600 dark:text-gray-300">
        Are you sure you want to delete the selected data?
    </p>

    {#snippet footer()}
        <Button clicked={deleteUserData} title="Delete" color="red"
                class="sm:ml-3"/>
        <Button clicked={() => modal.close()} title="Cancel" color="white"
                class="hidden mt-3 sm:inline-flex sm:mt-0"/>
    {/snippet}
</Modal>
