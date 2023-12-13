<script context="module">
    import {writable} from 'svelte/store';

    export const title = writable(null);
    export const showHeader = writable(true);
</script>

<script>
    import {inertia, Link, page} from '@inertiajs/svelte';
    import LinkModal from "@/Partials/LinkModal.svelte";
    import DeleteLinkModal from "@/Partials/DeleteLinkModal.svelte";
    import {route, dispatchCustomEvent, clickOutside} from '@/utils';
    import DesktopMenuItem from "@/Components/Navigation/DesktopMenuItem.svelte";
    import DeleteTagModel from "@/Partials/DeleteTagModel.svelte";
    import CommandPalette from "@/Partials/CommandPalette.svelte";
    import DeleteGroupModal from "@/Partials/DeleteGroupModal.svelte";
    import TagSelectMenu from "@/Partials/TagSelectMenu.svelte";
    import {onMount} from "svelte";
    import axios from "axios";
    import GroupNavigationLink from "@/Layouts/AppLayout/Partials/GroupNavigationLink.svelte";
    import GroupModal from "@/Partials/GroupModal.svelte";
    import {refreshGroups} from "@/stores.js";
    import {slide, fade} from "svelte/transition";
    import {cubicIn, cubicOut} from "svelte/easing";
    import {sidebarIsOpen, toggleSidebar} from "@/utils/local-settings.js";

    const appName = $page.props.appName;

    let showProfileDropdown = false;
    let groups = [];
    let showSidebar = sidebarIsOpen();

    $: $refreshGroups && getAllGroups();

    function handleKeydown(event) {
        if (event.target.tagName === 'INPUT') {
            return;
        }

        if (event.metaKey || event.altKey || event.shiftKey || event.ctrlKey) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        event.key === 'f'

        switch (event.key) {
            case 'f':
                dispatchCustomEvent('showCommandPalette');
                break;
            case 'm':
                showSidebar = toggleSidebar();
                break;
        }
    }

    async function getAllGroups() {
        await axios.get('/all-groups')
            .then(response => {
                groups = response.data
            });
    }

    onMount(() => {
        getAllGroups();
    });
</script>

<svelte:head>
    <title>{$title ? `${$title} | ${appName}` : appName}</title>
</svelte:head>

<svelte:window on:keydown={handleKeydown} on:toggleSidebar={() => showSidebar = toggleSidebar()}/>

<div class="flex min-h-screen">
    {#if showSidebar}
        <!-- Background overlay -->
        <div on:click={() => showSidebar = toggleSidebar()}
             on:keydown={() => showSidebar = toggleSidebar()}
             tabindex="0" role="button"
             in:fade={{ duration: 100, easing: cubicOut }}
             out:fade={{ duration: 200, easing: cubicIn }}
             class="absolute inset-0 z-40 bg-gray-500/40 lg:hidden"></div>
    {/if}

    <button on:click={() => showSidebar = toggleSidebar()} type="button"
            class="fixed bottom-[env(safe-area-inset-bottom)] left-4 z-10 mb-4 py-1.5 px-2 text-gray-100 bg-gray-800 rounded-md shadow sm:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
             class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
        </svg>
    </button>

    {#if showSidebar}
        <div in:slide={{ duration: 200, axis: 'x', easing: cubicOut }}
             out:slide={{ duration: 300, axis: 'x', easing: cubicOut }}
             class="fixed top-0 z-50 flex-none pb-[env(safe-area-inset-bottom)] w-[300px] h-screen lg:sticky">
            <div class="flex flex-col p-4 w-full h-full bg-gray-800 shadow">
                <div class="flex items-center">
                    <!-- Sidebar toggle -->
                    <button on:click={() => showSidebar = toggleSidebar()} type="button"
                            title={showSidebar ? 'Hide sidebar' : 'Show sidebar'}
                            class="hidden py-1.5 px-2 text-gray-200 rounded-md transition hover:text-white hover:bg-gray-700 hover:ring-1 hover:ring-gray-500 sm:block">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                             class="w-6 h-6">
                            <rect width="24" height="24" fill="none"/>
                            <rect x="2" y="3" width="20.3012" height="18.3152" rx="3" stroke="currentColor"
                                  stroke-width="2"
                                  stroke-linejoin="round"/>
                            <path
                                d="M2 6C2 4.34314 3.34315 3 5 3H10.1646V21.3152H5C3.34315 21.3152 2 19.9721 2 18.3152V6Z"
                                fill="currentColor"/>
                        </svg>
                    </button>

                    <!-- Search button -->
                    <button on:click={() => dispatchCustomEvent('showCommandPalette')} title="Open search bar"
                            type="button"
                            class="ml-auto py-1.5 px-2 text-gray-200 rounded-md transition hover:text-white hover:bg-gray-700 hover:ring-1 hover:ring-gray-500">
                        <span class="sr-only">Open search bar</span>
                        <!-- Heroicon name: outline/search -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>

                <nav class="mt-4 sm:mt-8">
                    <ul class="space-y-2">
                        <li>
                            <DesktopMenuItem title="Home" url={route('links.index')}/>
                        </li>
                        <li>
                            <DesktopMenuItem title="Tags" url={route('tags.index')}/>
                        </li>
                    </ul>
                </nav>

                <div class="flex justify-between items-center mt-6 sm:mt-12">
                    <div class="text-xs text-gray-200 font-semibold uppercase">Groups</div>

                    <button on:click={() => dispatchCustomEvent('createGroup', $page.props.group?.id)} type="button"
                            class="rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             class="w-6 h-6 fill-gray-100 hover:fill-white">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>

                </div>
                <div class="flex flex-col grow gap-y-2 my-4 overflow-auto">
                    {#each groups.filter((singleGroup) => !singleGroup.parentGroupId) as group}
                        <GroupNavigationLink group={group} groups={groups}/>
                    {/each}
                </div>

                <!-- Profile dropdown -->
                <div class="relative mt-auto h-8 w-8">
                    <button on:click={() => showProfileDropdown = !showProfileDropdown} type="button"
                            class="max-w-xs h-8 w-8 rounded-full overflow-hidden bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                            id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    {#if showProfileDropdown}
                        <div use:clickOutside on:click_outside={() => showProfileDropdown = false}
                             class="origin-bottom-left absolute bottom-0 left-full ml-3 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                             role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                             tabindex="-1">

                            <Link href={route('profile.show')}
                                  on:click={() => showProfileDropdown = false}
                                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                                  role="menuitem" tabindex="-1"
                                  id="user-menu-item-0">Your Profile
                            </Link>

                            <Link href={route('api-tokens.index')}
                                  on:click={() => showProfileDropdown = false}
                                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                                  role="menuitem" tabindex="-1"
                                  id="user-menu-item-1">API Tokens
                            </Link>

                            <button use:inertia={{ href: route('logout'), method: 'post' }}
                                    on:click={() => showProfileDropdown = false}
                                    type="button"
                                    class="block px-4 py-2 w-full text-left text-sm text-gray-700 hover:bg-gray-50"
                                    role="menuitem" tabindex="-1" id="user-menu-item-2">
                                Log Out
                            </button>
                        </div>
                    {/if}
                </div>
            </div>
        </div>
    {/if}

    <main class="flex-1">
        <div class="hidden p-4 sm:flex sm:items-center">
            {#if !showSidebar}
                <button on:click={() => showSidebar = toggleSidebar()} type="button"
                        title={showSidebar ? 'Hide sidebar' : 'Show sidebar'}
                        in:fade={{ duration: 100, easing: cubicOut }}
                        out:fade={{ duration: 50, easing: cubicIn }}
                        class="py-1.5 px-2 bg-white shadow-sm rounded-md ring-1 ring-gray-200 transition hover:bg-gray-50">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6 text-gray-600 transition duration-300 delay-150">
                        <rect width="24" height="24" fill="none"/>
                        <rect x="2" y="3" width="20.3012" height="18.3152" rx="3" stroke="currentColor" stroke-width="2"
                              stroke-linejoin="round"/>
                        <path d="M2 6C2 4.34314 3.34315 3 5 3H10.1646V21.3152H5C3.34315 21.3152 2 19.9721 2 18.3152V6Z"
                              fill="currentColor"/>
                    </svg>
                </button>
            {/if}

            <div class="py-1.5">
                <div class="h-6"></div>
            </div>
        </div>

        <slot/>
    </main>
</div>

<LinkModal/>
<GroupModal/>
<DeleteLinkModal/>
<DeleteTagModel/>
<DeleteGroupModal/>
<CommandPalette/>
<TagSelectMenu/>
