<script>
    import ListItem from "@/Components/LinkList/ListItem.svelte";
    import EmptyState from "@/Components/LinkList/EmptyState.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import Modal from "@/Components/Modals/Modal.svelte";

    export let links = [];
    export let modalTitle = 'Links';

    let showModal = false;
</script>

<ul {...$$restProps}
    class={['h-full bg-gray-200 divide-y divide-gray-200 overflow-hidden', $$restProps.class].join(' ').trim()}>
    {#each links as link (link.id)}
        <ListItem {link} showDateOnMobile={false}/>

    {:else}
        <EmptyState/>
    {/each}
</ul>

<!-- "More" button -->
{#if links.length > 0}
    <div class="absolute inset-x-0 bottom-0 flex justify-around p-3 bg-white">
        <button on:click={() => showModal = true} type="button"
                class="inline-flex items-center focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            <span class="ml-1.5 text-sm text-gray-500 font-medium">
                show list
            </span>
        </button>
    </div>
{/if}

<!-- "More" modal -->
<Modal title={modalTitle} fullWidthContent bind:showModal>
    <ul
        class="h-full bg-gray-200 divide-y divide-gray-200 border-y border-gray-200">
        {#each links as link (link.id)}
            <ListItem {link} withShadow={false}/>

        {:else}
            <EmptyState/>
        {/each}
    </ul>

    <svelte:fragment slot="footer">
        <Button on:clicked={() => showModal = false} title="Back" color="white"
                class="focus:ring-offset-gray-50"/>
    </svelte:fragment>
</Modal>
