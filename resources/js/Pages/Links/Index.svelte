<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import {inertia} from '@inertiajs/svelte';
    import Pagination from "@/Components/Pagination.svelte";
    import {router} from '@inertiajs/svelte';
    import {dispatchCustomEvent, route, toggleValueInArray} from "@/utils";
    import Badge from "@/Components/Badge.svelte";
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import {debounce} from "lodash";
    import GroupSelectMenu from "@/Partials/GroupSelectMenu.svelte";
    import TagSelectMenu from "@/Partials/TagSelectMenu.svelte";
    import Dropdown from "@/Components/Dropdowns/Dropdown.svelte";
    import InnerDropdownSection from "@/Components/Dropdowns/InnerDropdownSection.svelte";
    import DropdownItem from "@/Components/Dropdowns/DropdownItem.svelte";
    import SimpleModal from "@/Components/Modals/SimpleModal.svelte";

    export let links = [];
    export let tags = [];

    let filteredTags = [];
    let searchString = '';

    let groupSelectMenu;
    let tagSelectMenu;
    let deleteLinksModal;

    let bulkEditingEnabled = false;
    let bulkEditingAction = '';

    let selectedLinks = [];
    let selectedGroups = [];
    let selectedTags = [];

    let showBulkEditingDropdown = false;

    $title = 'Links';

    const search = debounce(() => {
        filterLinks();
    }, 400);

    const clearSearchInput = () => {
        searchString = '';
        search();
    }

    function addTagsToFilteredTags(tagId) {
        let index = filteredTags.indexOf(tagId);
        if (index !== -1) {
            filteredTags.splice(index, 1);
            filteredTags = filteredTags;
        } else {
            filteredTags = [...filteredTags, tagId];
        }

        filterLinks();
    }

    function filterLinks() {
        if (searchString === '') {
            searchString = null;
        }

        router.get(route(route().current(), {
            tags: filteredTags,
            search: searchString,
        }), {}, {
            only: ['links'],
            preserveState: true,
        });
    }

    function bulkEditLinks(action) {
        router.post('/bulk-edit-links', {
            action: action,
            links: selectedLinks,
            groups: selectedGroups,
            tags: selectedTags
        }, {
            only: ['links'],
            preserveScroll: true,
            onSuccess: () => {
                selectedLinks = (action === 'delete') ? [] : selectedLinks;
                selectedGroups = [];
                groupSelectMenu.reset();
                selectedGroups = [];
                tagSelectMenu.reset();
                bulkEditingAction = '';
            },
        });
    }

    function openGroupSelectMenu(action) {
        groupSelectMenu.enableMultiSelectMode();
        groupSelectMenu.openModal();

        bulkEditingAction = action;
    }

    function openTagSelectMenu(action) {
        tagSelectMenu.openModal();

        bulkEditingAction = action;
    }

    function toggleBulkEditingMode() {
        if (bulkEditingEnabled) {
            showBulkEditingDropdown = false;

            selectedLinks = [];
            tagSelectMenu.reset();
            groupSelectMenu.reset();

            bulkEditingEnabled = false;
        } else {
            bulkEditingEnabled = true;
        }
    }
</script>

<Main>
    <div slot="toolbar" class="sm:flex sm:items-center">
        <div class="relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                     fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
            <input type="text" bind:value={searchString} on:input={search}
                   class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                   placeholder="Search links..." enterkeyhint="search">
        </div>
        {#if searchString}
            <button on:click={clearSearchInput} type="button"
                    class="ml-2 text-sm text-gray-700">
                Clear
            </button>
        {/if}
    </div>

    <!-- Tag filter list -->
    {#if tags.length !== 0}
        <div class="px-4 text-gray-800 text-sm font-medium sm:px-0">Filter by tags</div>
        <div class="px-4 space-y-2 -mt-1 sm:px-0 sm:space-y-3">
            {#each tags as tag (tag.id)}
                <Badge on:clicked={() => addTagsToFilteredTags(tag.name)} title={tag.name}
                       color={filteredTags.includes(tag.name) ? '' : 'gray'}
                       class="bg-gray-50 first:mt-2 last:mr-0 sm:mr-1.5 sm:first:mt-3"/>
            {/each}

            {#if filteredTags.length > 0}
                <button on:click={() => {filteredTags = []; filterLinks()}} type="button"
                        class="text-gray-700 text-sm">
                    Clear all
                </button>
            {/if}
        </div>
    {/if}

    <!-- Bulk editing -->
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
                <Dropdown showDropdown={showBulkEditingDropdown}>
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

    <!-- Link list -->
    <ul class="grid grid-cols-1 gap-3 mt-6 sm:grid-cols-2">
        {#each links.data as link (link.id)}
            <li class="flex shadow sm:rounded-lg">
                {#if bulkEditingEnabled}
                    <!-- Show checkbox -->
                    <button on:click={() => selectedLinks = toggleValueInArray(selectedLinks, link.id)} type="button"
                            class="flex-none flex items-center justify-center w-16 group bg-gray-50 hover:cursor-pointer sm:w-20 sm:rounded-l-lg">
                        <input bind:group={selectedLinks} value={link.id} type="checkbox" class="text-primary-500 focus:outline-primary-400"/>
                    </button>
                {/if}

                <a href={route('links.show', link.id)} use:inertia
                   class={['flex-auto min-w-0 p-3 bg-white sm:px-6 sm:py-4', bulkEditingEnabled ? 'sm:rounded-r-lg' : 'sm:rounded-l-lg'].join(' ').trim()}>
                    <div class="flex justify-between">
                        <div
                            class="text-sm font-medium text-gray-900 truncate">{link.title}</div>
                        <div class="text-xs text-gray-700">
                            {link.created_at}
                        </div>
                    </div>
                    <div class="mt-0.5 text-xs text-gray-500 truncate">
                        {link.link}
                    </div>

                    <!-- Tags -->
                    {#if link.tags.length}
                        <div class="space-y-4">
                            {#each link.tags as tag (tag.id)}
                                <Badge title={tag.name} large={false} noHover={true}
                                       class="mr-1.5 first:mt-4 last:mr-0"/>
                            {/each}
                        </div>
                    {/if}

                    <!-- Groups -->
                    {#if link.groups.length}
                        <div class="space-y-4">
                            {#each link.groups as group}
                                <Badge title={group.title} large={false} noHover={true} rounded={true} color="gray"
                                       class="mr-1.5 first:mt-4 last:mr-0"/>
                            {/each}
                        </div>
                    {/if}
                </a>

                {#if !bulkEditingEnabled}
                    <!-- Open link in new tab -->
                    <a href={link.link} target="_blank" rel="noreferrer noopener nofollow" title="Open the link"
                       class="flex-none flex items-center justify-center w-16 group bg-gray-50 hover:cursor-pointer sm:w-20 sm:rounded-r-lg">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6 text-gray-300 group-hover:text-gray-500"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                {/if}
            </li>

        {:else}
            <div class="col-span-2 container mx-auto">
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

    <Pagination links={links.links} class="mt-6"/>
</Main>

<TagSelectMenu on:changesSaved={() => bulkEditLinks(bulkEditingAction)} bind:this={tagSelectMenu} bind:selectedTags/>
<GroupSelectMenu on:changesSaved={() => bulkEditLinks(bulkEditingAction)} bind:this={groupSelectMenu}
                 bind:selectedGroups/>
<SimpleModal title="Delete selected links" description="Are you sure you want to delete the selected links?"
             buttonText="Delete" buttonColor="red" bind:this={deleteLinksModal}
             on:actionButtonClicked={() => bulkEditLinks('delete')}/>
