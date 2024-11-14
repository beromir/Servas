<script context="module">
    import AppLayout, {title, showHeader} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import {onDestroy} from 'svelte';
    import {dispatchCustomEvent, route} from "@/utils";
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import DropdownItem from "@/Components/Dropdowns/DropdownItem.svelte";
    import Dropdown from "@/Components/Dropdowns/Dropdown.svelte";
    import InnerDropdownSection from "@/Components/Dropdowns/InnerDropdownSection.svelte";
    import LinkListWithTagFilter from "@/Components/LinkList/LinkListWithTagFilter.svelte";
    import {router} from "@inertiajs/svelte";
    import Modal from "@/Components/Modals/Modal.svelte";
    import Button from "@/Components/Buttons/Button.svelte";

    export let group = {};
    export let links = [];
    export let publicLink = {};
    export let searchString = '';
    export let filteredTags = [];
    export let showUntaggedOnly = false;

    let showMenuDropdown = false;
    let showPublicLinkModal = false;
    let publicLinkCopied = false;

    $: $title = group.title;
    $showHeader = false;

    function handleGroupMenuAction(action) {
        dispatchCustomEvent(action, group);
        showMenuDropdown = false;
    }

    function createPublicLink() {
        router.post(route('publicLinks.store'), {
            groupId: group.id,
        }, {
            only: ['publicLink'],
            onSuccess: () => {
                showPublicLinkModal = true;
            }
        });
    }

    function showPublicLinkDeleteModal() {
        showPublicLinkModal = false;

        dispatchCustomEvent('deletePublicLink', {id: publicLink.id, title: group.title});
    }

    function copyLink(link) {
        navigator.clipboard.writeText(link);
        publicLinkCopied = true;

        setTimeout(() => publicLinkCopied = false, 500);
    }

    onDestroy(() => {
        $showHeader = true;
    });
</script>

<Main title={group.title}>
    <svelte:fragment slot="toolbar">
        <div class="relative inline-flex ml-auto">
            <button on:click={() => showMenuDropdown = !showMenuDropdown} type="button"
                    class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                </svg>
            </button>

            <Dropdown bind:showDropdown={showMenuDropdown} openingDirection="left"
                      class="top-full !mt-0 !w-44 !origin-top-right">
                <InnerDropdownSection>
                    <DropdownItem on:clicked={() => handleGroupMenuAction('editGroup')} title="Edit">
                        <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z"/>
                            <path
                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z"/>
                        </svg>
                    </DropdownItem>

                    {#if !publicLink.id}
                        <DropdownItem on:clicked={createPublicLink} title="Share">
                            <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M12.232 4.232a2.5 2.5 0 0 1 3.536 3.536l-1.225 1.224a.75.75 0 0 0 1.061 1.06l1.224-1.224a4 4 0 0 0-5.656-5.656l-3 3a4 4 0 0 0 .225 5.865.75.75 0 0 0 .977-1.138 2.5 2.5 0 0 1-.142-3.667l3-3Z"/>
                                <path
                                    d="M11.603 7.963a.75.75 0 0 0-.977 1.138 2.5 2.5 0 0 1 .142 3.667l-3 3a2.5 2.5 0 0 1-3.536-3.536l1.225-1.224a.75.75 0 0 0-1.061-1.06l-1.224 1.224a4 4 0 1 0 5.656 5.656l3-3a4 4 0 0 0-.225-5.865Z"/>
                            </svg>
                        </DropdownItem>
                    {/if}

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

        {#if publicLink.id}
            <Button on:clicked={() => showPublicLinkModal = true} color="white" hoverTitle="Public link" class="ml-4 !w-auto">
                <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path
                        d="M12.232 4.232a2.5 2.5 0 0 1 3.536 3.536l-1.225 1.224a.75.75 0 0 0 1.061 1.06l1.224-1.224a4 4 0 0 0-5.656-5.656l-3 3a4 4 0 0 0 .225 5.865.75.75 0 0 0 .977-1.138 2.5 2.5 0 0 1-.142-3.667l3-3Z"/>
                    <path
                        d="M11.603 7.963a.75.75 0 0 0-.977 1.138 2.5 2.5 0 0 1 .142 3.667l-3 3a2.5 2.5 0 0 1-3.536-3.536l1.225-1.224a.75.75 0 0 0-1.061-1.06l-1.224 1.224a4 4 0 1 0 5.656 5.656l3-3a4 4 0 0 0-.225-5.865Z"/>
                </svg>
            </Button>
        {/if}
    </svelte:fragment>

    {#if links.length !== 0}
        <LinkListWithTagFilter {links} {searchString} {filteredTags} {showUntaggedOnly}/>
    {/if}
</Main>

<Modal title="Public link" bind:showModal={showPublicLinkModal}>
    <p class="text-sm text-gray-600 dark:text-gray-300">
        Share this group with this link:
    </p>

    <div class="flex justify-between items-center gap-x-4 mt-1">
        <div class="text-sm text-gray-700 font-medium break-all select-all dark:text-gray-50">{publicLink.link}</div>

        <button on:click={() => copyLink(publicLink.link, publicLink.id)} type="button"
                class="flex items-center gap-x-1.5 group">
            {#if publicLinkCopied}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                     class="size-5 fill-green-500">
                    <path fill-rule="evenodd"
                          d="M18 5.25a2.25 2.25 0 0 0-2.012-2.238A2.25 2.25 0 0 0 13.75 1h-1.5a2.25 2.25 0 0 0-2.238 2.012c-.875.092-1.6.686-1.884 1.488H11A2.5 2.5 0 0 1 13.5 7v7h2.25A2.25 2.25 0 0 0 18 11.75v-6.5ZM12.25 2.5a.75.75 0 0 0-.75.75v.25h3v-.25a.75.75 0 0 0-.75-.75h-1.5Z"
                          clip-rule="evenodd"/>
                    <path fill-rule="evenodd"
                          d="M3 6a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H3Zm6.874 4.166a.75.75 0 1 0-1.248-.832l-2.493 3.739-.853-.853a.75.75 0 0 0-1.06 1.06l1.5 1.5a.75.75 0 0 0 1.154-.114l3-4.5Z"
                          clip-rule="evenodd"/>
                </svg>

            {:else}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                     class="size-5 fill-gray-500 group-hover:fill-primary-500 dark:fill-gray-300 dark:group-hover:fill-primary-400">
                    <path fill-rule="evenodd"
                          d="M13.887 3.182c.396.037.79.08 1.183.128C16.194 3.45 17 4.414 17 5.517V16.75A2.25 2.25 0 0 1 14.75 19h-9.5A2.25 2.25 0 0 1 3 16.75V5.517c0-1.103.806-2.068 1.93-2.207.393-.048.787-.09 1.183-.128A3.001 3.001 0 0 1 9 1h2c1.373 0 2.531.923 2.887 2.182ZM7.5 4A1.5 1.5 0 0 1 9 2.5h2A1.5 1.5 0 0 1 12.5 4v.5h-5V4Z"
                          clip-rule="evenodd"/>
                </svg>
            {/if}

            <span class="text-sm text-gray-700 font-medium dark:text-gray-200">Copy</span>
        </button>
    </div>

    <svelte:fragment slot="footer">
        <Button on:clicked={showPublicLinkDeleteModal} color="red"
                class="focus:ring-offset-gray-50">
            <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z"
                      clip-rule="evenodd"/>
            </svg>
        </Button>
        <Button on:clicked={() => showPublicLinkModal = false} title="Close" color="white"
                class="hidden focus:ring-offset-gray-50 sm:block"/>
    </svelte:fragment>
</Modal>
