<script>
    import Modal from "@/Components/Modals/Modal.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import {router} from '@inertiajs/svelte';
    import {route} from '@/utils';
    import {refreshGroups} from "@/stores";

    let group = $state(null);
    let modal = $state();

    function prepareDeleteGroup(e) {
        group = e.detail;

        modal.show();
    }

    function deleteGroup() {
        router.delete(route('groups.destroy', group.id), {
            preserveScroll: true,
            onSuccess: () => {
                modal.close();
                refreshGroups.update();
            },
        });
    }
</script>

<svelte:window ondeleteGroup={prepareDeleteGroup}/>

<Modal bind:this={modal} title="Delete Group">
    {#if group}
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Are you sure you want to delete this group?
        </p>
        <div class="mt-1 text-sm text-gray-700 font-medium dark:text-gray-50">
            {group.title}
        </div>
    {/if}

    {#snippet footer()}

        <Button clicked={() => modal.close()} title="Cancel" color="white"
                class="hidden sm:block"/>
        <Button clicked={deleteGroup} title="Delete" color="red"/>

    {/snippet}
</Modal>
