<script>
    import Modal from "@/Components/Modals/Modal.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import {router} from '@inertiajs/svelte';
    import {route} from '@/utils';
    import {refreshGroups} from "@/stores";

    let showModal = false;
    let group = null;

    function prepareDeleteGroup(e) {
        group = e.detail;

        showModal = true;
    }

    function deleteGroup() {
        router.delete(route('groups.destroy', group.id), {
            preserveScroll: true,
            onSuccess: () => {
                showModal = false;
                refreshGroups.update();
            },
        });
    }
</script>

<svelte:window on:deleteGroup={prepareDeleteGroup}/>

<Modal title="Delete Group" bind:showModal>
    <p class="text-sm text-gray-600 dark:text-gray-300">
        Are you sure you want to delete this group?
    </p>
    <div class="mt-1 text-sm text-gray-700 font-medium dark:text-gray-50">
        {group.title}
    </div>

    <svelte:fragment slot="footer">
        <Button on:clicked={() => showModal = false} title="Cancel" color="white"
                class="hidden focus:ring-offset-gray-50 sm:block"/>
        <Button on:clicked={deleteGroup} title="Delete" color="red" focusButton={true}
                class="focus:ring-offset-gray-50"/>
    </svelte:fragment>
</Modal>
