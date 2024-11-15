<script>
    import {dispatchCustomEvent, route, toggleValueInArray} from "@/utils";
    import Pagination from "@/Components/Pagination.svelte";
    import {Link, router} from "@inertiajs/svelte";
    import GroupSelectMenu from "@/Partials/GroupSelectMenu.svelte";
    import DropdownItem from "@/Components/Dropdowns/DropdownItem.svelte";
    import Dropdown from "@/Components/Dropdowns/Dropdown.svelte";
    import InnerDropdownSection from "@/Components/Dropdowns/InnerDropdownSection.svelte";
    import {selectedTags} from "@/stores.js";
    import {debounce} from "lodash";
    import Button from "@/Components/Buttons/Button.svelte";
    import Modal from "@/Components/Modals/Modal.svelte";
    import {createEventDispatcher, onDestroy} from "svelte";
    import EmptyStateWithAction from "@/Components/EmptyStates/EmptyStateWithAction.svelte";
    import LinkIcon from "@/Heroicons/Outline/Link.svelte";

    export let links = [];
    export let searchString = '';

    const dispatch = createEventDispatcher();

    let groupSelectMenu;

    let showBulkEditingDropdown = false;
    let showLinkDeletionModal = false;

    let bulkEditingEnabled = false;
    let bulkEditingAction = '';

    let selectedLinks = [];
    let selectedGroups = [];

    $: $selectedTags.tags.length > 0 && $selectedTags.action && bulkEditLinks($selectedTags.action);

    function bulkEditLinks(action) {
        router.post('/bulk-edit-links', {
            action: action,
            links: selectedLinks,
            groups: selectedGroups,
            tags: $selectedTags.tags.map(item => item.id),
        }, {
            only: ['links'],
            preserveScroll: true,
            onSuccess: () => {
                selectedLinks = (action === 'delete') ? [] : selectedLinks;
                selectedGroups = [];
                groupSelectMenu.reset();
                selectedGroups = [];
                bulkEditingAction = '';
                selectedTags.reset();
            },
        });
    }

    function openGroupSelectMenu(action) {
        groupSelectMenu.enableMultiSelectMode();
        groupSelectMenu.openModal();

        bulkEditingAction = action;
    }

    function openTagSelectMenu(action) {
        $selectedTags.action = action;

        dispatchCustomEvent('tags.select', {
            title: action === 'attachTags' ? 'Attach tags' : 'Detach tags',
            buttonTitle: action === 'attachTags' ? 'Attach' : 'Detach',
        });

        window.addEventListener('tags.selected', (e) => {
            $selectedTags.tags = e.detail;

        }, {once: true});

        bulkEditingAction = action;
    }

    function toggleBulkEditingMode() {
        if (bulkEditingEnabled) {
            showBulkEditingDropdown = false;

            selectedLinks = [];
            groupSelectMenu.reset();

            bulkEditingEnabled = false;
        } else {
            bulkEditingEnabled = true;
        }
    }

    const search = debounce(() => {
        dispatch('searched', searchString);
    }, 400);

    const clearSearchInput = () => {
        searchString = '';
        search();
    }

    onDestroy(() => {
        selectedTags.reset();
    });
</script>

<div class="px-4 sm:px-0">
    <!-- Search input -->
    <div class="flex flex-col gap-y-4 sm:flex-row sm:gap-x-6">
        <div class="w-full sm:flex sm:items-center sm:w-auto">
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-400 dark:text-gray-500"
                         viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
                <input type="text" bind:value={searchString} on:input={search}
                       class="block w-full pl-10 border-gray-400 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-600"
                       placeholder="Search links..." enterkeyhint="search">
            </div>
            {#if searchString}
                <button on:click={clearSearchInput} type="button"
                        class="ml-2 text-sm text-gray-700 dark:text-gray-200">
                    Clear
                </button>
            {/if}
        </div>

        <slot name="toolbar"/>
    </div>

    <slot/>

    <!-- Edit links -->
    {#if links.data.length}
        <div class="flex items-center gap-x-4 mt-6 sm:flex-row-reverse">
            <button on:click={toggleBulkEditingMode} type="button"
                    class="group inline-flex items-center font-medium text-sm text-gray-700 hover:text-gray-800 dark:text-gray-200 dark:hover:text-gray-300">
                <svg
                    class="mr-2 size-4 flex-none text-gray-500 group-hover:text-gray-600 dark:text-gray-300 dark:group-hover:text-gray-400"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z"/>
                </svg>
                Edit links
            </button>

            {#if bulkEditingEnabled}
                <div class="relative inline-flex">
                    <button on:click={() => showBulkEditingDropdown = !showBulkEditingDropdown} type="button"
                            class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-800 dark:text-gray-200 dark:hover:text-gray-300">
                        Action
                        <svg
                            class="-mr-1 ml-1 size-5 flex-shrink-0 text-gray-500 group-hover:text-gray-600 dark:text-gray-300 dark:group-hover:text-gray-400"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <Dropdown bind:showDropdown={showBulkEditingDropdown}>
                        <InnerDropdownSection title="Tags">
                            <DropdownItem on:clicked={() => openTagSelectMenu('attachTags')} title="Attach tags">
                                <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path
                                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
                                </svg>
                            </DropdownItem>
                            <DropdownItem on:clicked={() => openTagSelectMenu('detachTags')} title="Detach tags">
                                <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </DropdownItem>
                        </InnerDropdownSection>
                        <InnerDropdownSection title="Groups">
                            <DropdownItem on:clicked={() => openGroupSelectMenu('attachGroups')} title="Attach groups">
                                <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path
                                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
                                </svg>
                            </DropdownItem>
                            <DropdownItem on:clicked={() => openGroupSelectMenu('detachGroups')} title="Detach groups">
                                <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </DropdownItem>
                        </InnerDropdownSection>
                        <InnerDropdownSection>
                            <DropdownItem on:clicked={() => showLinkDeletionModal = true} title="Delete links"
                                          color="alert">
                                <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </DropdownItem>
                        </InnerDropdownSection>
                    </Dropdown>
                </div>

                {#if selectedLinks.length === 0}
                    <button on:click={() => selectedLinks = links.data.map(x => x.id)} type="button"
                            class="text-sm font-medium text-gray-700 hover:text-gray-800 dark:text-gray-200 dark:hover:text-gray-300">
                        Select all
                    </button>

                {:else}
                    <button on:click={() => selectedLinks = []} type="button"
                            class="text-sm font-medium text-gray-700 hover:text-gray-800 dark:text-gray-200 dark:hover:text-gray-300">
                        Deselect all
                    </button>
                {/if}
            {/if}
        </div>
    {/if}
</div>

<!-- Link list -->
<ul class="grid grid-cols-1 mt-2 divide-y divide-gray-200 sm:grid-cols-2 sm:gap-3 sm:mt-4 sm:divide-none dark:divide-gray-800">
    {#each links.data as link (link.id)}
        <li class="flex bg-white shadow ring-contrast sm:overflow-hidden sm:rounded-lg dark:bg-gray-800">
            {#if bulkEditingEnabled}
                <!-- Show checkbox -->
                <button on:click={() => selectedLinks = toggleValueInArray(selectedLinks, link.id)} type="button"
                        class="flex-none flex items-center justify-center w-16 group bg-gray-50 hover:cursor-pointer sm:w-20 dark:bg-gray-900">
                    <input bind:group={selectedLinks} value={link.id} type="checkbox"
                           class="text-primary-500 focus:outline-primary-400 dark:bg-gray-800 dark:focus:ring-offset-gray-700"/>
                </button>
            {/if}

            <Link href={route('links.show', link.id)}
                  class="flex-auto min-w-0 p-3 sm:px-6 sm:py-4">
                <div class="flex justify-between">
                    <div title={link.title}
                         class="text-sm font-medium text-gray-900 truncate dark:text-white">{link.title}</div>
                </div>
                <div class="mt-0.5 text-xs text-gray-500 truncate dark:text-gray-400">
                    {new URL(link.link).host}
                </div>
            </Link>

            {#if !bulkEditingEnabled}
                <div class="py-2 pr-2">
                    <!-- Open link in new tab -->
                    <a href={link.link} target="_blank" rel="noreferrer noopener nofollow" title="Open the link"
                       class="flex-none flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="size-6 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            {/if}
        </li>

    {:else}
        <EmptyStateWithAction on:clicked={() => dispatchCustomEvent('prepareCreateNewLink')} title="Add a new link"
                              class="mt-2 px-4 col-span-2 sm:px-0">
            <LinkIcon slot="icon"/>
        </EmptyStateWithAction>
    {/each}
</ul>

{#if links.data.length && links.total > links.per_page}
    <Pagination prevPageUrl={links.prev_page_url} nextPageUrl={links.next_page_url} currentPage={links.current_page}
                totalPages={Math.trunc(links.total / links.per_page) + 1} totalLinks={links.total}/>
{/if}
<GroupSelectMenu on:changesSaved={() => bulkEditLinks(bulkEditingAction)} bind:this={groupSelectMenu}
                 bind:selectedGroups/>

<Modal title="Delete selected links" bind:showModal={showLinkDeletionModal}>
    <p class="text-sm text-gray-500">
        Are you sure you want to delete the selected links?
    </p>

    <svelte:fragment slot="footer">
        <Button on:clicked={() => showLinkDeletionModal = false} title="Cancel" color="white"
                class="hidden focus:ring-offset-gray-50 sm:block"/>
        <Button on:clicked={() => bulkEditLinks('delete')} title="Delete" color="red" focusButton={true}
                class="focus:ring-offset-gray-50"/>
    </svelte:fragment>
</Modal>
