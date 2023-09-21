<script>
    import Button from "@/Components/Buttons/Button.svelte";
    import Modal from '@/Components/Modals/Modal.svelte';
    import {isUndefined} from "lodash";
    import {selectedTags, linkFilter} from "@/stores.js";

    let mode = '';
    let tags = [];
    let internalSelectedTags = [];          // property for internal use only; contains tag objects
    let showModal = false;
    let showUntaggedButton = false;
    let showUntagged = false;

    $: showUntaggedButton = mode === Mode.Filter;

    const Mode = {
        Select: 'select',
        Filter: 'filter',
    };

    function prepareTagFilter() {
        internalSelectedTags = $linkFilter.tags;
        showUntagged = $linkFilter.showUntaggedOnly;

        openModal();

        mode = Mode.Filter;
    }

    function prepareTagSelection() {
        openModal();

        mode = Mode.Select;
    }

    export async function openModal() {
        await getAllTags();

        showModal = true;
    }

    function saveChanges() {
        $selectedTags = [];
        $linkFilter.tags = [];

        internalSelectedTags.forEach((tag) => {
            if (mode === Mode.Select) {
                $selectedTags = [...$selectedTags, tag];
            } else if (mode === Mode.Filter) {
                $linkFilter.tags = [...$linkFilter.tags, tag];
            }
        });

        $linkFilter.showUntaggedOnly = showUntagged;

        showModal = false;
    }

    async function getAllTags() {
        await axios.get('/all-tags')
            .then(response => {
                tags = response.data
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

    export async function setSelectedTags(tagsToSet) {
        internalSelectedTags = [];
        $selectedTags = [];

        if (!tagsToSet.length) {
            return;
        }

        await getAllTags();

        tagsToSet.forEach((tagId) => {
            let tag = tags.find(({id}) => {
                return id === tagId;
            })

            if (!isUndefined(tag)) {
                internalSelectedTags = [...internalSelectedTags, tags];
            }
        });

        $selectedTags = tagsToSet;
    }

    function getIndexOfTagId(tagId, arrayToSearch) {
        return arrayToSearch.findIndex(tag => tag.id === tagId);
    }

    function cancel() {
        showModal = false;
        internalSelectedTags = [];
        showUntagged = false;
    }

    function reset() {
        $selectedTags = [];
        $linkFilter.tags = [];
        $linkFilter.showUntaggedOnly = false;
        internalSelectedTags = [];
        showUntagged = false;
        mode = '';
    }
</script>

<svelte:window on:filterTags={prepareTagFilter} on:selectTags={prepareTagSelection}/>

<Modal title="Select tags" bind:showModal>
    <div class="relative mt-5">
        <div class="grid grid-cols-2 gap-y-4 gap-x-2 mt-3 pb-10">
            {#each tags as tag (tag.id)}
                {#if getIndexOfTagId(tag.id, internalSelectedTags) !== -1}
                    <button on:click|stopPropagation={() => selectTag(tag)} type="button"
                            class="flex items-center py-3 px-4 border border-primary-100 bg-primary-50 rounded-full hover:border-primary-200 focus:outline-none">
                        <span class="w-2 h-2 bg-primary-500 rounded-full"></span>
                        <span class="ml-3 text-sm text-primary-900 truncate">{tag.name}</span>
                    </button>
                {:else}
                    <button on:click|stopPropagation={() => selectTag(tag)} type="button"
                            class="flex items-center py-3 px-4 border border-gray-100 bg-gray-50 rounded-full hover:border-gray-200 focus:outline-none">
                        <span class="w-2 h-2 bg-primary-500 rounded-full"></span>
                        <span class="ml-3 text-sm text-gray-900 truncate">{tag.name}</span>
                    </button>
                {/if}
            {:else}
                <li class="py-2.5 px-4 text-gray-600 text-center">No tags found</li>
            {/each}
        </div>

        <div class="absolute bottom-0 left-1/2">
            {#if internalSelectedTags.length}
                <button on:click={() => {internalSelectedTags = []; showUntagged = false}} type="button"
                        class="absolute bottom-0 left-0 flex items-center w-max text-gray-600 -translate-x-1/2 group hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                         class="w-5 h-5 fill-gray-400 group-hover:fill-gray-500">
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
                         class="w-5 h-5 fill-gray-400 group-hover:fill-gray-500">
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
                         class="w-5 h-5 fill-gray-400 group-hover:fill-gray-500">
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
        <Button on:clicked={saveChanges} title="OK"
                class="focus:ring-offset-gray-50 sm:ml-3"/>
        <Button on:clicked={cancel} title="Cancel" color="white"
                class="hidden mt-3 focus:ring-offset-gray-50 sm:inline-flex sm:mt-0"/>
    </svelte:fragment>
</Modal>
