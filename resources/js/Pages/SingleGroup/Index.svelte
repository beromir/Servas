<script context="module">
    import AppLayout, {title, showHeader} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import {onDestroy} from 'svelte';
    import {dispatchCustomEvent} from "@/utils";
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import LinkList from "@/Components/LinkList/LinkList.svelte";
    import DropdownItem from "@/Components/Dropdowns/DropdownItem.svelte";
    import Dropdown from "@/Components/Dropdowns/Dropdown.svelte";
    import InnerDropdownSection from "@/Components/Dropdowns/InnerDropdownSection.svelte";

    export let group = {};
    export let parentGroups = [];
    export let links = [];

    let showMenuDropdown = false;

    $: $title = group.title;
    $showHeader = false;

    onDestroy(() => {
        $showHeader = true;
    });

    function handleGroupMenuAction(action) {
        dispatchCustomEvent(action, group);
        showMenuDropdown = false;
    }
</script>

<Main title={group.title}>
    <svelte:fragment slot="toolbar">
        <div class="relative inline-flex mr-6 ml-auto">
            <button on:click={() => showMenuDropdown = !showMenuDropdown} type="button"
                    class="text-gray-600 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                </svg>
            </button>
            <Dropdown bind:showDropdown={showMenuDropdown} class="top-full !mt-0 !w-44 !origin-top-right">
                <InnerDropdownSection>
                    <DropdownItem on:clicked={() => handleGroupMenuAction('editGroup')} title="Edit">
                        <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z"/>
                            <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z"/>
                        </svg>
                    </DropdownItem>
                    <DropdownItem on:clicked={() => handleGroupMenuAction('deleteGroup')} title="Delete"
                                  color="alert">
                        <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </DropdownItem>
                </InnerDropdownSection>
            </Dropdown>
        </div>
    </svelte:fragment>

    <div class="mt-6">

        {#if links.length !== 0}
            <LinkList {links}/>
        {/if}
    </div>
</Main>
