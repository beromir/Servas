<script>
    import {dispatchCustomEvent, route, toggleValueInArray} from "@/utils";
    import Pagination from "@/Components/Pagination.svelte";
    import {Link, router} from "@inertiajs/svelte";
    import SimpleModal from "@/Components/Modals/SimpleModal.svelte";
    import GroupSelectMenu from "@/Partials/GroupSelectMenu.svelte";
    import DropdownItem from "@/Components/Dropdowns/DropdownItem.svelte";
    import Dropdown from "@/Components/Dropdowns/Dropdown.svelte";
    import InnerDropdownSection from "@/Components/Dropdowns/InnerDropdownSection.svelte";
    import {selectedTags} from "@/stores.js";
    import {onDestroy} from "svelte";

    export let links = [];

    let groupSelectMenu;
    let deleteLinksModal;

    let showBulkEditingDropdown = false;

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

        dispatchCustomEvent('selectTags', {title: action});

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

    onDestroy(() => {
        selectedTags.reset();
    });
</script>

<div class="flex mt-4 px-4 space-x-6 sm:px-0 md:flex-row-reverse md:space-x-reverse">
    <button on:click={toggleBulkEditingMode} type="button"
            class="group inline-flex items-center font-medium text-sm text-gray-700">
        <svg class="mr-2 h-4 w-4 flex-none text-gray-400 group-hover:text-gray-500"
             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path
                d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z"/>
        </svg>
        Edit links
    </button>

    {#if bulkEditingEnabled}
        <div class="relative inline-flex">
            <button on:click={() => showBulkEditingDropdown = !showBulkEditingDropdown} type="button"
                    class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                Action
                <svg class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                          clip-rule="evenodd"/>
                </svg>
            </button>
            <Dropdown bind:showDropdown={showBulkEditingDropdown}>
                <InnerDropdownSection title="Tags">
                    <DropdownItem on:clicked={() => openTagSelectMenu('attachTags')} title="Attach tags">
                        <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
                        </svg>
                    </DropdownItem>
                    <DropdownItem on:clicked={() => openTagSelectMenu('detachTags')} title="Detach tags">
                        <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </DropdownItem>
                </InnerDropdownSection>
                <InnerDropdownSection title="Groups">
                    <DropdownItem on:clicked={() => openGroupSelectMenu('attachGroups')} title="Attach groups">
                        <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
                        </svg>
                    </DropdownItem>
                    <DropdownItem on:clicked={() => openGroupSelectMenu('detachGroups')} title="Detach groups">
                        <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </DropdownItem>
                </InnerDropdownSection>
                <InnerDropdownSection>
                    <DropdownItem on:clicked={deleteLinksModal.openModal} title="Delete links" color="alert">
                        <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
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
                    class="text-sm font-medium text-gray-700 hover:text-gray-900">
                Select all
            </button>

        {:else}
            <button on:click={() => selectedLinks = []} type="button"
                    class="text-sm font-medium text-gray-700 hover:text-gray-900">
                Deselect all
            </button>
        {/if}
    {/if}
</div>

<ul class="grid grid-cols-1 mt-6 divide-y divide-gray-200 sm:grid-cols-2 sm:gap-3 sm:divide-none">
    {#each links.data as link (link.id)}
        <li class="flex bg-white shadow sm:overflow-hidden sm:rounded-lg">
            {#if bulkEditingEnabled}
                <!-- Show checkbox -->
                <button on:click={() => selectedLinks = toggleValueInArray(selectedLinks, link.id)} type="button"
                        class="flex-none flex items-center justify-center w-16 group bg-gray-50 hover:cursor-pointer sm:w-20">
                    <input bind:group={selectedLinks} value={link.id} type="checkbox"
                           class="text-primary-500 focus:outline-primary-400"/>
                </button>
            {/if}

            <Link href={route('links.show', link.id)}
                  class="flex-auto min-w-0 p-3 sm:px-6 sm:py-4">
                <div class="flex justify-between">
                    <div
                        class="text-sm font-medium text-gray-900 truncate">{link.title}</div>
                </div>
                <div class="mt-0.5 text-xs text-gray-500 truncate">
                    {new URL(link.link).host}
                </div>
            </Link>

            {#if !bulkEditingEnabled}
                <div class="py-2 pr-2">
                    <!-- Open link in new tab -->
                    <a href={link.link} target="_blank" rel="noreferrer noopener nofollow" title="Open the link"
                       class="flex-none flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6 text-gray-300 hover:text-gray-500"
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
        <div class="col-span-2 container mx-auto px-4 sm:px-0">
            <button on:click="{() => dispatchCustomEvent('prepareCreateNewLink')}" type="button"
                    class="relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 focus:ring-offset-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                <span class="mt-2 block text-sm font-medium text-gray-900">
                    Add a new link
                </span>
            </button>
        </div>
    {/each}
</ul>

{#if links.data.length && links.total > links.per_page}
    <Pagination prevPageUrl={links.prev_page_url} nextPageUrl={links.next_page_url} currentPage={links.current_page}
                totalPages={Math.trunc(links.total / links.per_page) + 1}/>
{/if}
<GroupSelectMenu on:changesSaved={() => bulkEditLinks(bulkEditingAction)} bind:this={groupSelectMenu}
                 bind:selectedGroups/>
<SimpleModal title="Delete selected links" description="Are you sure you want to delete the selected links?"
             buttonText="Delete" buttonColor="red" bind:this={deleteLinksModal}
             on:actionButtonClicked={() => bulkEditLinks('delete')}/>
