<script>
    import {router} from '@inertiajs/svelte'
    import {fade, scale} from 'svelte/transition';
    import {backIn, backOut} from 'svelte/easing';
    import {debounce, delay} from "lodash";
    import {route, noScroll} from '@/utils';
    import {refreshLinks} from "@/stores";

    let searchResults = [];
    let ungroupedSearchResults = [];
    let selectedModal = null;
    let showCommandPalette = false;
    let input;

    $: searchResults ? searchResults.forEach(item => ungroupedSearchResults.push(item.items)) : []; console.log(ungroupedSearchResults);

    function openCommandPalette() {
        searchResults = [];
        selectedModal = null;
        showCommandPalette = true;

        delay(() => focus(), 200);
    }

    function closeCommandPalette() {
        showCommandPalette = false;
    }

    function openSingleModalPage(url) {
        if (selectedModal) {
            closeCommandPalette();

            router.get(url);
        }
    }

    function handleKeydown(e) {
        switch (e.key) {
            case 'Escape':
                e.preventDefault();
                closeCommandPalette();
                break;
            case 'ArrowDown':
            case 'ArrowUp':
                e.preventDefault();
                if (searchResults.length) {
                    if (selectedModal === null) {
                        selectedModal = e.key === 'ArrowDown' ? searchResults[0].hash : searchResults[searchResults.length - 1].hash;
                    } else {
                        let index = searchResults.findIndex((e) => {
                            return e.hash === selectedModal;
                        });

                        if (e.key === 'ArrowDown' ? searchResults[++index] : searchResults[--index]) {
                            selectedModal = searchResults[index].hash;
                        } else {
                            selectedModal = e.key === 'ArrowDown' ? searchResults[0].hash : searchResults[searchResults.length - 1].hash;
                        }
                    }
                    document.getElementById(`select-option-${selectedModal}`).scrollIntoView({block: 'nearest'})
                }

                break;
            case 'Tab':
                e.preventDefault();
                break;
            case 'Enter':
                e.preventDefault();
                openSingleModalPage();

                break;
            default:
                delay(() => {
                    let index = searchResults.findIndex((e) => {
                        return e.hash === selectedModal;
                    });

                    if (index !== -1) {
                        document.getElementById(`select-option-${selectedModal}`).scrollIntoView({block: 'nearest'})
                    } else {
                        selectedModal = null;
                        if (document.getElementById('options')) {
                            document.getElementById('options').scrollTo(0, 0)
                        }
                    }
                }, 10);
        }
    }

    const search = debounce(() => {
        axios.post(route('search'), {
            searchString: input.value.trim().toLowerCase(),
        }).then(response => {
            searchResults = response.data;
        });
    }, 400);

    const focus = () => input.focus();
</script>

<svelte:window on:showCommandPalette={openCommandPalette}/>

{#if showCommandPalette}
    <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20" role="dialog" aria-modal="true">

        <!-- Background overlay, show/hide based on modal state. -->
        <div on:click={closeCommandPalette}
             in:fade={{duration: 300, easing: backOut}}
             out:fade={{duration: 200, easing: backIn}}
             class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity" aria-hidden="true">
        </div>

        <!-- Command palette, show/hide based on modal state. -->
        <div in:scale={{duration: 300, start: 0.95, easing: backOut}}
             out:scale={{duration: 200, start: 0.95, easing: backIn}}
             use:noScroll
             class="mx-auto max-w-xl transform rounded-xl bg-white shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
            <div class="p-2">
                <input bind:this={input} on:input={search} on:keydown={handleKeydown} type="text"
                       class="w-full rounded-md border-0 bg-gray-100 px-4 py-2.5 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm"
                       placeholder="Search..." role="combobox" aria-expanded="false" aria-controls="options"
                       autocorrect="off" autocapitalize="off" spellcheck="false" enterkeyhint="search">
            </div>

            <!-- Results, show/hide based on command palette state. -->
            {#if searchResults.length}
                <div class="max-h-72 overflow-y-auto text-sm text-gray-800">
                    {#each searchResults as group}

                        <div
                            class="sticky top-0 py-2 px-6 bg-gray-50 text-gray-800 text-xs font-semibold">
                            {group.title}
                        </div>
                        <ul class="pt-1 pb-3 px-2" id="options">
                            {#each group.items as result (result.hash)}
                                <!-- Active: "bg-indigo-600 text-white" -->
                                <li on:mouseenter={() => selectedModal = result.hash}
                                    on:mouseleave={() => selectedModal = null}
                                    on:click={() => openSingleModalPage(result.url)}
                                    id={`select-option-${result.hash}`}
                                    class={['cursor-default select-none rounded-md px-4 py-2',
                                        result.hash === selectedModal ? 'bg-primary-500 text-white' : ''].join(' ').trim()}
                                    tabindex="-1" aria-hidden="true">
                                    <div>{result.title}</div>
                                    {#if result.link}
                                        <div class={['text-xs truncate',
                                            result.hash === selectedModal ? 'text-gray-100' : 'text-gray-600'].join(' ').trim()}>
                                            {result.link}
                                        </div>
                                    {/if}
                                </li>
                            {/each}
                        </ul>
                    {/each}
                </div>
            {/if}

            <!-- Empty state, show/hide based on command palette state. -->
            {#if (input && (input.value.length > 1 && !searchResults.length))}
                <div class="py-14 px-4 text-center sm:px-14">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-6 w-6 text-gray-500" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-4 text-sm text-gray-900">Nothing found using that search term.</p>
                </div>
            {/if}
        </div>
    </div>
{/if}
