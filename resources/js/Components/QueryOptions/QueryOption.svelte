<script>
    import {dispatchCustomEvent, toggleValueInArray} from "@/utils/index.js";
    import XMark from "@/Heroicons/Micro/XMark.svelte";
    import Plus from "@/Heroicons/Micro/Plus.svelte";

    export let tags = [];
    export let optionMode = '';                 // 'or', 'and', 'not'
    export let title = '';

    function handleSelectTagsButtonClick() {
        dispatchCustomEvent('tags.select', {tags: tags});

        window.addEventListener('tags.selected', (e) => {
            switch (optionMode) {
                case 'or':
                    tags = e.detail;
                    break;
                case 'and':
                    tags = e.detail;
                    break;
                case 'not':
                    tags = e.detail;
            }
        }, {once: true});
    }
</script>

<div>
    <div class="text-sm text-gray-700 font-medium">{title}</div>

    {#if tags.length}
        {#each tags as tag (tag.id)}
            <button on:click={() => tags = toggleValueInArray(tags, tag)} type="button"
                    class="inline-flex items-center mr-2 mt-2 py-0.5 px-2.5 bg-primary-100 text-sm text-primary-800 font-medium rounded-full group">
                <XMark
                    className="mr-1 -ml-1 fill-primary-500 rounded-full group-hover:fill-primary-700 group-hover:bg-primary-200/50"/>
                {tag.name}
            </button>
        {/each}
    {/if}

    <button on:click={handleSelectTagsButtonClick} type="button"
            class="inline-flex items-center mr-2 mt-2 py-0.5 px-2.5 text-sm text-gray-700 font-medium border border-gray-100 rounded-full group">
        <Plus
            className="mr-1 -ml-1 fill-gray-500 rounded-full group-hover:fill-gray-700 group-hover:bg-gray-200/40"/>
        add tag
    </button>
</div>
