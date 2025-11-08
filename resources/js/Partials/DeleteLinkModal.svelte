<script>
    import Modal from "@/Components/Modals/Modal.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import {router} from '@inertiajs/svelte';
    import {route} from '@/utils';
    import {refreshLinks} from "@/stores";

    let link = $state(null);
    let modal = $state();

    function prepareDeleteLink(e) {
        link = e.detail;

        modal.show();
    }

    function deleteLink() {
        router.delete(route('links.destroy', link.id), {
            preserveScroll: true,
            onSuccess: () => {
                modal.close();
                $refreshLinks = true;
            },
        });
    }
</script>

<svelte:window ondeleteLink={prepareDeleteLink}/>

<Modal bind:this={modal} title="Delete Link">
    {#if link}
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Are you sure you want to delete this link?
        </p>
        <div class="mt-1 text-sm text-gray-700 font-medium dark:text-gray-50">
            {link.title}
        </div>
    {/if}

    {#snippet footer()}
        <Button clicked={() => modal.close()} title="Cancel" color="white"
                class="hidden sm:block"/>
        <Button clicked={deleteLink} title="Delete" color="red"/>
    {/snippet}
</Modal>
