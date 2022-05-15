<script>
    import {Inertia} from '@inertiajs/inertia'
    import {fade, scale} from 'svelte/transition';
    import {backIn, backOut} from 'svelte/easing';
    import {delay} from "lodash";
    import {route, noScroll} from '@/utils';
    import {refreshLinks} from "@/stores";

    let filteredLinks = [];
    let selectedLink = null;
    let showCommandPalette = false;
    let input;

    // $: links = $page.props.allLinks;

    let links = [];

    function openCommandPalette() {
        if ($refreshLinks) {
            axios.get('/all-links')
                .then(response => {
                    links = response.data
                })

            $refreshLinks = false;
        }

        filteredLinks = [];
        selectedLink = null;
        showCommandPalette = true;

        delay(() => focus(), 200);
    }

    function closeCommandPalette() {
        showCommandPalette = false;
    }

    function filter() {
        filteredLinks = input.value.length < 2
            ? []
            : links.filter((link) => {
                if (link.title.toLowerCase().includes(input.value.trim().toLowerCase())) {
                    return link.title.toLowerCase().includes(input.value.trim().toLowerCase());
                }
                return link.link.toLowerCase().includes(input.value.trim().toLowerCase());
            });
    }

    function openSingleLinkPage() {
        if (selectedLink) {
            closeCommandPalette();
            Inertia.get(route('links.show', selectedLink));
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
                if (filteredLinks.length) {
                    if (selectedLink === null) {
                        selectedLink = e.key === 'ArrowDown' ? filteredLinks[0].id : filteredLinks[filteredLinks.length - 1].id;
                    } else {
                        let index = filteredLinks.findIndex((e) => {
                            return e.id === selectedLink;
                        });

                        if (e.key === 'ArrowDown' ? filteredLinks[++index] : filteredLinks[--index]) {
                            selectedLink = filteredLinks[index].id;
                        } else {
                            selectedLink = e.key === 'ArrowDown' ? filteredLinks[0].id : filteredLinks[filteredLinks.length - 1].id;
                        }
                    }
                    document.getElementById(`select-option-${selectedLink}`).scrollIntoView({block: 'nearest'})
                }

                break;
            case 'Tab':
                e.preventDefault();
                break;
            case 'Enter':
                e.preventDefault();
                openSingleLinkPage();

                break;
            default:
                delay(() => {
                    let index = filteredLinks.findIndex((e) => {
                        return e.id === selectedLink;
                    });

                    if (index !== -1) {
                        document.getElementById(`select-option-${selectedLink}`).scrollIntoView({block: 'nearest'})
                    } else {
                        selectedLink = null;
                        if (document.getElementById('options')) {
                            document.getElementById('options').scrollTo(0, 0)
                        }
                    }
                }, 10);
        }
    }

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
             class="mx-auto max-w-xl transform rounded-xl bg-white p-2 shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
            <input bind:this={input} on:input={filter} on:keydown={handleKeydown} type="text"
                   class="w-full rounded-md border-0 bg-gray-100 px-4 py-2.5 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm"
                   placeholder="Search..." role="combobox" aria-expanded="false" aria-controls="options"
                   autocorrect="off" autocapitalize="off" spellcheck="false" enterkeyhint="search">

            <!-- Results, show/hide based on command palette state. -->
            {#if filteredLinks.length}
                <ul class="-mb-2 max-h-72 scroll-py-2 overflow-y-auto py-2 text-sm text-gray-800" id="options"
                    role="listbox">
                    {#each filteredLinks as link (link.id)}
                        <!-- Active: "bg-indigo-600 text-white" -->
                        <li on:mouseenter={() => selectedLink = link.id} on:mouseleave={() => selectedLink = null}
                            on:click={openSingleLinkPage}
                            id={`select-option-${link.id}`}
                            class={['cursor-default select-none rounded-md px-4 py-2',
                            link.id === selectedLink ? 'bg-primary-500 text-white' : ''].join(' ').trim()}
                            role="option" tabindex="-1">
                            <div class="font-medium">{link.title}</div>
                            <div class={['text-xs truncate',
                                link.id === selectedLink ? 'text-gray-100' : 'text-gray-600'].join(' ').trim()}>
                                {link.link}
                            </div>
                        </li>
                    {/each}
                </ul>
            {/if}

            <!-- Empty state, show/hide based on command palette state. -->
            {#if (input && (input.value.length > 1 && !filteredLinks.length))}
                <div class="py-14 px-4 text-center sm:px-14">
                    <!-- Heroicon name: outline/link -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-6 w-6 text-gray-400" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <p class="mt-4 text-sm text-gray-900">No links found using that search term.</p>
                </div>
            {/if}
        </div>
    </div>
{/if}
