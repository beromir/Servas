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
    <div class="mt-5">
        <div class="grid grid-cols-2 gap-y-4 gap-x-2 mt-3">
            {#each tags as tag (tag.id)}
                {#if getIndexOfTagId(tag.id, internalSelectedTags) !== -1}
                    <button on:click|stopPropagation={() => selectTag(tag)} type="button"
                            class="flex items-center py-3 px-4 border border-primary-200 bg-primary-100 rounded-full hover:border-primary-300 focus:outline-none">
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
    </div>

    <svelte:fragment slot="footer">
        <Button on:clicked={saveChanges} title="OK"
                class="focus:ring-offset-gray-50 sm:ml-3"/>
        <Button on:clicked={() => showModal = false} title="Cancel" color="white"
                class="hidden mt-3 focus:ring-offset-gray-50 sm:inline-flex sm:mt-0"/>
    </svelte:fragment>
</Modal>
