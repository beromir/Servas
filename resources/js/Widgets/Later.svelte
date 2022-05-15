<script>
    import Widget from "@/Components/Widgets/Widget.svelte";
    import ListContainer from "@/Components/LinkList/ListContainer.svelte";

    export let later = {};

    let filterBy = 'all';

    $: filteredLaterLinks = later.links.filter(link => {
        if (filterBy === 'all') {
            return true;
        }

        return link.mediaType === filterBy;
    });
</script>

<Widget title="Later" fixedHeight={true}>
    <div class="sm:hidden">
        <div
            class="relative z-10 flex justify-center px-5 py-2.5 rounded-t-lg bg-white shadow">
            <select bind:value={filterBy}
                    class="block w-full border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                <option value="all">All</option>

                <option value="text">Text</option>

                <option value="audio">Audio</option>

                <option value="video">Video</option>

                <option value="image">Image</option>
            </select>
        </div>
    </div>
    <div class="hidden sm:block">
        <nav
            class="relative z-10 flex justify-center space-x-4 px-5 py-3 bg-white shadow"
            aria-label="Tabs">
            <!-- Current: "bg-indigo-100 text-indigo-700", Default: "text-gray-500 hover:text-gray-700" -->
            <button on:click={() => filterBy = 'all'} type="button"
                    class={['px-3 py-2 font-medium text-sm rounded-md',
                    filterBy === 'all' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700'].join(' ').trim()}>
                All
            </button>

            <button on:click={() => filterBy = 'text'} type="button"
                    class={['px-3 py-2 font-medium text-sm rounded-md',
                    filterBy === 'text' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700'].join(' ').trim()}>
                Text
            </button>

            <button on:click={() => filterBy = 'audio'} type="button"
                    class={['px-3 py-2 font-medium text-sm rounded-md',
                    filterBy === 'audio' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700'].join(' ').trim()}>
                Audio
            </button>

            <button on:click={() => filterBy = 'video'} type="button"
                    class={['px-3 py-2 font-medium text-sm rounded-md',
                    filterBy === 'video' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700'].join(' ').trim()}>
                Video
            </button>

            <button on:click={() => filterBy = 'image'} type="button"
                    class={['px-3 py-2 font-medium text-sm rounded-md',
                    filterBy === 'image' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700'].join(' ').trim()}>
                Image
            </button>
        </nav>
    </div>

    <ListContainer links={filteredLaterLinks} modalTitle="Later"/>
</Widget>
