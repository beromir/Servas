<script>
    import {router} from '@inertiajs/svelte'
    import {debounce, delay} from "lodash";
    import {route} from '@/utils';
    import Home from "@/Heroicons/Mini/Home.svelte";
    import Inbox from "@/Heroicons/Mini/Inbox.svelte";
    import Tag from "@/Heroicons/Mini/Tag.svelte";
    import Link from "@/Heroicons/Mini/Link.svelte";
    import clsx from "clsx";

    const pages = [
        {id: 'home', title: 'Home', icon: Home, url: route('links.index')},
        {id: 'inbox', title: 'Inbox', icon: Inbox, url: route('inbox.index')},
        {id: 'tags', title: 'Tags', icon: Tag, url: route('tags.index')},
        {id: 'shared-groups', title: 'Shared Groups', icon: Link, url: route('publicLinks.index')},
    ];

    let searchResults = $state([]);
    let groups = $state([]);
    let selectedModal = $state(null);
    let selectedPage = $state(null);
    let dialog;
    let inputValue = $state('');

    function openCommandPalette() {
        searchResults = [];
        selectedModal = null;
        selectedPage = null;
        inputValue = '';
        dialog.showModal();
    }

    function closeCommandPalette() {
        dialog.close();
    }

    function openPage(url) {
        if (selectedPage) {
            closeCommandPalette();

            router.get(url);
        }
    }

    function openSingleModalPage(url) {
        if (selectedModal) {
            closeCommandPalette();

            router.get(url);
        }
    }

    function handleKeydown(e) {
        switch (e.key) {
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

                    break;
                }

                if (selectedPage === null) {
                    selectedPage = e.key === 'ArrowDown' ? pages[0].id : pages[pages.length - 1].id;
                } else {
                    let index = pages.findIndex((e) => {
                        return e.id === selectedPage;
                    });

                    if (e.key === 'ArrowDown' ? pages[++index] : pages[--index]) {
                        selectedPage = pages[index].id;
                    } else {
                        selectedPage = e.key === 'ArrowDown' ? pages[0].id : pages[pages.length - 1].id;
                    }
                }

                break;
            case 'Tab':
                e.preventDefault();
                break;
            case 'Enter':
                e.preventDefault();

                if (searchResults.length) {
                    let url = searchResults.find(result => result.hash === selectedModal).url;

                    openSingleModalPage(url);

                    break;
                }

                let url = pages.find(result => result.id === selectedPage).url;

                openPage(url);

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
                        let firstGroup = groups[0];
                        if (firstGroup && document.getElementById(`options-${firstGroup}`)) {
                            document.getElementById(`options-${firstGroup}`).scrollTo(0, 0)
                        }
                    }
                }, 10);
        }
    }

    function getGroups() {
        groups = [];

        searchResults.forEach(result => {
            if (!groups.includes(result.type)) {
                groups.push(result.type);
            }
        });
    }

    function getSearchResultsByGroup(group) {
        let resultsByGroup = [];

        searchResults.forEach(result => {
            if (result.type === group) {
                resultsByGroup.push(result);
            }
        });

        return resultsByGroup;
    }

    const search = debounce(() => {
        const searchString = inputValue.trim().toLowerCase();

        if (!searchString) {
            searchResults = [];
            groups = [];
            selectedModal = null;

            return;
        }

        axios.post(route('search'), {
            searchString: searchString,
        }).then(response => {
            searchResults = response.data;
            getGroups();
        });
    }, 400);

    function handleDialogClick(e) {
        if (e.target === dialog) closeCommandPalette();
    }
</script>

<svelte:window onshowCommandPalette={openCommandPalette}/>

<dialog bind:this={dialog} onclick={handleDialogClick}
        aria-label="Command palette"
        class="mx-4 my-4 w-full rounded-xl shadow-2xl ring-contrast backdrop:bg-gray-500/75 sm:mx-auto sm:my-6 sm:md:max-w-xl md:my-20 dark:bg-gray-950 dark:backdrop:bg-gray-900/75">
    <div class="p-2">
        <input bind:value={inputValue}
               oninput={search}
               onkeydown={handleKeydown}
               type="text"
               class="w-full rounded-md border-0 bg-gray-200 px-4 py-2.5 placeholder-gray-600 focus:ring-0 sm:text-sm dark:bg-gray-900 dark:text-white dark:placeholder-gray-400"
               placeholder="Search..."
               role="combobox"
               aria-expanded={searchResults.length > 0 || inputValue.length < 1}
               aria-controls="command-palette-listbox"
               aria-activedescendant={selectedModal ? `select-option-${selectedModal}` : (selectedPage ? `select-option-${selectedPage}` : '')}
               aria-autocomplete="list"
               aria-haspopup="listbox"
               autocapitalize="off"
               spellcheck="false">
    </div>

    <!-- Screen reader announcements -->
    <div role="status" aria-live="polite" aria-atomic="true" class="sr-only">
        {#if searchResults.length}
            {searchResults.length} result{searchResults.length === 1 ? '' : 's'} found
        {:else if inputValue.length > 1}
            No results found
        {/if}
    </div>

    <!-- Page navigation -->
    {#if (inputValue.length < 1 && !searchResults.length)}
        <div class="overflow-y-auto scroll-pt-10">
            <ul role="listbox"
                id="command-palette-listbox"
                aria-label="Quick navigation"
                class="pt-1 pb-3 px-2 text-gray-800 font-medium dark:text-gray-100">
                {#each pages as page}
                    <li role="option"
                        aria-selected={selectedPage === page.id}
                        onmouseenter={() => selectedPage = page.id}
                        onmouseleave={() => selectedPage = null}
                        onclick={() => openPage(page.url)}
                        onkeydown={(e) => e.key === 'Enter' && openPage(page.url)}
                        id={`select-option-${page.id}`}
                        class={clsx(
                            'flex items-center gap-x-3 px-4 py-2 cursor-default select-none rounded-md',
                            selectedPage === page.id ? 'bg-primary-500 text-white [&>svg]:text-gray-100 dark:bg-primary-700 dark:text-gray-100' : ''
                        )}>
                        <page.icon className="text-gray-500 dark:text-gray-300" aria-hidden="true"/>
                        {page.title}
                    </li>
                {/each}
            </ul>
        </div>
    {/if}

    <!-- Results, show/hide based on command palette state. -->
    {#if searchResults.length}
        <div id="command-palette-listbox"
             class="max-h-72 overflow-y-auto scroll-pt-10 text-sm text-gray-800 dark:text-gray-100">
            {#each groups as group}
                <div role="group" aria-labelledby={`group-label-${group}`}>
                    <div id={`group-label-${group}`}
                         role="presentation"
                         class="sticky top-0 py-2 px-6 bg-gray-100 text-gray-800 text-xs font-semibold dark:bg-gray-900 dark:text-gray-200">
                        {group}
                    </div>
                    <ul role="listbox"
                        aria-labelledby={`group-label-${group}`}
                        class="pt-1 pb-3 px-2"
                        id={`options-${group}`}>
                        {#each getSearchResultsByGroup(group) as result (result.hash)}
                            <li role="option"
                                aria-selected={result.hash === selectedModal}
                                onmouseenter={() => selectedModal = result.hash}
                                onmouseleave={() => selectedModal = null}
                                onclick={() => openSingleModalPage(result.url)}
                                onkeydown={(e) => e.key === 'Enter' && openSingleModalPage(result.url)}
                                id={`select-option-${result.hash}`}
                                class={['px-4 py-2 cursor-default select-none rounded-md',
                                            result.hash === selectedModal ? 'bg-primary-500 text-white dark:bg-primary-700 dark:text-gray-100' : ''].join(' ').trim()}>
                                <div>{result.title}</div>
                                {#if result.link}
                                    <div class={['text-xs truncate',
                                                result.hash === selectedModal ? 'text-gray-100 dark:text-gray-200' : 'text-gray-600 dark:text-gray-400'].join(' ').trim()}>
                                        {new URL(result.link).host}
                                    </div>
                                {/if}
                            </li>
                        {/each}
                    </ul>
                </div>
            {/each}
        </div>
    {/if}

    <!-- Empty state, show/hide based on command palette state. -->
    {#if (inputValue.length > 1 && !searchResults.length)}
        <div class="py-14 px-4 text-center sm:px-14">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="mx-auto size-6 text-gray-500 dark:text-gray-300"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.5"
                 stroke="currentColor"
                 aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="mt-4 text-sm text-gray-900 dark:text-gray-100">Nothing found using that search term.</p>
        </div>
    {/if}
</dialog>
