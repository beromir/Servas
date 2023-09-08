<script>
    import Button from "@/Components/Buttons/Button.svelte";
    import Modal from '@/Components/Modals/Modal.svelte';
    import {isUndefined} from "lodash";
    import {createEventDispatcher} from 'svelte';

    export let selectedTags = [];        // property to access from outside; contains tag IDs; will only change when clicking "OK"

    let tags = [];
    let internalSelectedTags = [];       // property for internal use only; contains tag objects
    let showModal = true;

    const dispatch = createEventDispatcher();

    export async function openModal() {
        await getAllTags();

        showModal = true;
    }

    function saveChanges() {
        selectedTags = [];

        internalSelectedTags.forEach((tag) => {
            selectedTags = [...selectedTags, tag.id];
        });

        showModal = false;

        dispatch('changesSaved');
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

    export function reset() {
        selectedTags = [];
        internalSelectedTags = [];
    }

    export async function setSelectedTags(tagsToSet) {
        internalSelectedTags = [];
        selectedTags = [];

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

        selectedTags = tagsToSet;
    }

    function getIndexOfTagId(tagId, arrayToSearch) {
        return arrayToSearch.findIndex(tag => tag.id === tagId);
    }

    getAllTags();
</script>

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
                <button on:click={() => internalSelectedTags = []} type="button"
                        class="absolute bottom-0 left-0 flex items-center w-max text-gray-700 -translate-x-1/2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                         class="w-5 h-5 fill-gray-500">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-1.5">
                        Deselect all
                    </span>
                </button>
            {/if}
        </div>
    </div>

    <svelte:fragment slot="footer">
        <Button on:clicked={saveChanges} title="OK"
                class="focus:ring-offset-gray-50 sm:ml-3"/>
        <Button on:clicked={() => showModal = false} title="Cancel" color="white"
                class="hidden mt-3 focus:ring-offset-gray-50 sm:inline-flex sm:mt-0"/>
    </svelte:fragment>
</Modal>
