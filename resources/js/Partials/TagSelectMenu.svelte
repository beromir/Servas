<script>
    import Button from "@/Components/Buttons/Button.svelte";
    import Modal from '@/Components/Modals/Modal.svelte';
    import {selectedTags, linkFilter} from "@/stores.js";

    let title = 'Select tags';
    let primaryButtonTitle = 'Save';
    let mode = '';
    let tags = [];
    let filteredTags = [];
    let internalSelectedTags = [];          // property for internal use only; contains tag objects
    let showModal = false;
    let showUntaggedButton = false;
    let showUntagged = false;
    let searchInput = null;

    $: showUntaggedButton = mode === Mode.Filter;

    const Mode = {
        Select: 'select',
        Filter: 'filter',
    };

    function prepareTagFilter() {
        internalSelectedTags = $linkFilter.tags;
        showUntagged = $linkFilter.showUntaggedOnly;

        mode = Mode.Filter;

        title = 'Filter by tags';
        primaryButtonTitle = 'Filter';

        openModal();
    }

    function prepareTagSelection(action) {
        internalSelectedTags = [];
        mode = Mode.Select;

        title = action === 'attachTags' ? 'Attach tags' : 'Detach tags';
        primaryButtonTitle = action === 'attachTags' ? 'Attach' : 'Detach';

        openModal();
    }

    export async function openModal() {
        await getAllTags();

        showModal = true;
    }

    function saveChanges() {
        $selectedTags.tags = [];
        $linkFilter.tags = [];

        internalSelectedTags.forEach((tag) => {
            if (mode === Mode.Select) {
                $selectedTags.tags = [...$selectedTags.tags, tag];
            } else if (mode === Mode.Filter) {
                $linkFilter.tags = [...$linkFilter.tags, tag];
            }
        });

        if (mode === Mode.Select) {

        } else if (mode === Mode.Filter) {
            $linkFilter.showUntaggedOnly = showUntagged;
            $linkFilter.isActive = true;
        }

        showModal = false;

        internalSelectedTags = [];
    }

    async function getAllTags() {
        await axios.get('/all-tags')
            .then(response => {
                tags = response.data;
                filteredTags = response.data;
            });
    }

    function selectTag(tag) {
        let index = getIndexOfTagId(tag.id, internalSelectedTags);

        if (index !== -1) {
            internalSelectedTags.splice(index, 1);
            internalSelectedTags = internalSelectedTags;
        } else {
            internalSelectedTags = [...internalSelectedTags, tag];
        }
    }

    function getIndexOfTagId(tagId, arrayToSearch) {
        return arrayToSearch.findIndex(tag => tag.id === tagId);
    }

    function search(searchTerm) {
        if (searchTerm) {
            filteredTags = tags.filter(tag => tag.name.toLowerCase().includes(searchTerm.toLowerCase()));
        } else {
            filteredTags = tags;
        }
    }

    function resetSearch() {
        searchInput.value = '';
        filteredTags = tags;
    }

    function cancel() {
        showModal = false;
        internalSelectedTags = [];
        showUntagged = false;
    }
</script>

<svelte:window on:filterTags={prepareTagFilter} on:selectTags={(e) => prepareTagSelection(e.detail.title)}/>

<Modal bind:showModal on:canceled={cancel} title={title} showFooterMenuOnMobile={false}>
    <svelte:fragment slot="mobilePrimaryAction">
        <button on:click={saveChanges}
                class="text-right text-primary-600 font-medium focus:outline-none sm:hidden" type="button">
            {primaryButtonTitle}
        </button>
    </svelte:fragment>

    <!-- Search input -->
    <div class="relative">
        <input on:input={(e) => search(e.target.value)} bind:this={searchInput} type="text" placeholder="Search tags..."
               class="px-10 w-full border-0 border-b border-gray-100 peer focus:border-primary-200 focus:outline-none focus:ring-0"/>

        <div class="absolute inset-y-0 left-2 flex items-center text-gray-300 peer-focus:text-primary-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                <path fill-rule="evenodd"
                      d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                      clip-rule="evenodd"/>
            </svg>
        </div>

        {#if searchInput?.value}
            <div class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-gray-600">
                <button on:click={resetSearch} type="button" title="Clear search">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" data-slot="icon"
                         class="size-4">
                        <path fill-rule="evenodd"
                              d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm2.78-4.22a.75.75 0 0 1-1.06 0L8 9.06l-1.72 1.72a.75.75 0 1 1-1.06-1.06L6.94 8 5.22 6.28a.75.75 0 0 1 1.06-1.06L8 6.94l1.72-1.72a.75.75 0 1 1 1.06 1.06L9.06 8l1.72 1.72a.75.75 0 0 1 0 1.06Z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        {/if}
    </div>

    <div class="relative mt-8">
        <div class="grid grid-cols-2 gap-y-4 gap-x-2 mt-3 pb-10">
            {#each filteredTags as tag (tag.id)}
                {#if getIndexOfTagId(tag.id, internalSelectedTags) !== -1}
                    <button on:click|stopPropagation={() => selectTag(tag)} type="button"
                            class="flex items-center py-3 px-4 border border-primary-100 bg-primary-50 rounded-full hover:border-primary-200 focus:outline-none">
                        <span class="size-2 bg-primary-500 rounded-full"></span>
                        <span class="ml-3 text-sm text-primary-900 truncate">{tag.name}</span>
                    </button>
                {:else}
                    <button on:click|stopPropagation={() => selectTag(tag)} type="button"
                            class="flex items-center py-3 px-4 border border-gray-100 bg-gray-50 rounded-full hover:border-gray-200 focus:outline-none">
                        <span class="size-2 bg-primary-500 rounded-full"></span>
                        <span class="ml-3 text-sm text-gray-900 truncate">{tag.name}</span>
                    </button>
                {/if}
            {:else}
                <p class="col-span-2 py-2.5 px-4 text-gray-600 text-center">No tags found</p>
            {/each}
        </div>

        <div class="absolute bottom-0 left-1/2">
            {#if internalSelectedTags.length}
                <button on:click={() => {internalSelectedTags = []; showUntagged = false}} type="button"
                        class="absolute bottom-0 left-0 flex items-center w-max text-gray-600 -translate-x-1/2 group hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                         class="size-5 fill-gray-400 group-hover:fill-gray-500">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-1.5">
                        Deselect all
                    </span>
                </button>
            {:else if showUntaggedButton && showUntagged}
                <button on:click={() => showUntagged = !showUntagged} type="button"
                        class="absolute bottom-0 left-0 flex items-center w-max text-gray-600 -translate-x-1/2 group hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                         class="size-5 fill-gray-400 group-hover:fill-gray-500">
                        <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/>
                        <path fill-rule="evenodd"
                              d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-1.5">
                        Show all
                    </span>
                </button>
            {:else if showUntaggedButton && !showUntagged}
                <button on:click={() => showUntagged = !showUntagged} type="button"
                        class="absolute bottom-0 left-0 flex items-center w-max text-gray-600 -translate-x-1/2 group hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                         class="size-5 fill-gray-400 group-hover:fill-gray-500">
                        <path fill-rule="evenodd"
                              d="M5.5 3A2.5 2.5 0 003 5.5v2.879a2.5 2.5 0 00.732 1.767l6.5 6.5a2.5 2.5 0 003.536 0l2.878-2.878a2.5 2.5 0 000-3.536l-6.5-6.5A2.5 2.5 0 008.38 3H5.5zM6 7a1 1 0 100-2 1 1 0 000 2z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-1.5">
                        Show untagged
                    </span>
                </button>
            {/if}
        </div>
    </div>

    <svelte:fragment slot="footer">
        <Button on:clicked={cancel} title="Cancel" color="white"
                class="focus:ring-offset-gray-50"/>
        <Button on:clicked={saveChanges} title={primaryButtonTitle}
                class="focus:ring-offset-gray-50"/>
    </svelte:fragment>
</Modal>
