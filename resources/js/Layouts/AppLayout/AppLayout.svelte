<script context="module">
    import {writable} from 'svelte/store';

    export const title = writable(null);
    export const showHeader = writable(true);
</script>

<script>
    import {inertia, Link, page} from '@inertiajs/svelte';
    import LinkModal from "@/Partials/LinkModal.svelte";
    import DeleteLinkModal from "@/Partials/DeleteLinkModal.svelte";
    import {route, dispatchCustomEvent} from '@/utils';
    import MenuItem from "@/Components/Navigation/MenuItem.svelte";
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
    import InnerDropdownSection from "@/Components/Dropdowns/InnerDropdownSection.svelte";
    import Dropdown from "@/Components/Dropdowns/Dropdown.svelte";
    import {closeSidebar} from "@/utils/sidebar.js";
    import DeletePublicLinkModal from "@/Partials/DeletePublicLinkModal.svelte";
    import DropdownItem from "@/Components/Dropdowns/DropdownItem.svelte";
    import Sun from "@/Heroicons/Mini/Sun.svelte";
    import Moon from "@/Heroicons/Mini/Moon.svelte";
    import ComputerDesktop from "@/Heroicons/Mini/ComputerDesktop.svelte";
    import {initTheme, changeTheme} from "@/utils/theme.js";
    import Home from "@/Heroicons/Mini/Home.svelte";
    import Inbox from "@/Heroicons/Mini/Inbox.svelte";
    import Tag from "@/Heroicons/Mini/Tag.svelte";
    import Plus from "@/Heroicons/Mini/Plus.svelte";
    import MenuButton from "@/Components/Navigation/MenuButton.svelte";

    const appName = $page.props.appName;
    const appVersion = $page.props.appVersion;
    const showAppVersion = $page.props.showAppVersion;

    let showProfileDropdown = false;
    let groups = [];
    let showSidebar = sidebarIsOpen();
    let theme = initTheme();

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
             class="fixed inset-0 z-40 bg-gray-500/40 lg:hidden dark:bg-gray-900/60"></div>
    {/if}

    <button on:click={() => showSidebar = toggleSidebar()} type="button"
            class="fixed bottom-5 left-5 z-10 p-2.5 text-gray-100 bg-gray-800 rounded-full shadow sm:hidden dark:bg-gray-50 dark:text-gray-900">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
             class="size-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
        </svg>
    </button>

    {#if showSidebar}
        <div in:slide={{ duration: 200, axis: 'x', easing: cubicOut }}
             out:slide={{ duration: 300, axis: 'x', easing: cubicOut }}
             class="fixed top-0 z-50 flex-none w-[300px] h-screen lg:sticky">
            <div
                class="flex flex-col w-full h-dvh bg-gray-700 shadow ring-contrast transition duration-300 dark:bg-gray-800">
                <div class="flex items-center p-4">
                    <!-- Sidebar toggle -->
                    <button on:click={() => showSidebar = toggleSidebar()} type="button"
                            title={showSidebar ? 'Hide sidebar' : 'Show sidebar'}
                            class="hidden py-1.5 px-2 text-gray-200 rounded-md transition hover:text-white hover:bg-gray-600 hover:ring-1 hover:ring-gray-500 sm:block dark:hover:bg-gray-700">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                             class="size-6">
                            <rect width="24" height="24" fill="none"/>
                            <rect x="2" y="3" width="20.3012" height="18.3152" rx="3" stroke="currentColor"
                                  stroke-width="2"
                                  stroke-linejoin="round"/>
                            <path
                                d="M2 6C2 4.34314 3.34315 3 5 3H10.1646V21.3152H5C3.34315 21.3152 2 19.9721 2 18.3152V6Z"
                                fill="currentColor"/>
                        </svg>
                    </button>

                    <!-- Profile dropdown -->
                    <div class="relative sm:ml-auto">
                        <button on:click={() => showProfileDropdown = !showProfileDropdown} title="Open user menu"
                                type="button"
                                class="py-1.5 px-2 text-gray-200 rounded-md transition hover:text-white hover:bg-gray-600 hover:ring-1 hover:ring-gray-500 dark:hover:bg-gray-700">
                            <span class="sr-only">Open user menu</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>

                        <Dropdown bind:showDropdown={showProfileDropdown} openingDirection="right"
                                  class="top-full !mt-0 !w-44 !origin-top-right">
                            <InnerDropdownSection>
                                <Link href={route('profile.show')}
                                      on:click={() => {showProfileDropdown = false; closeSidebar()}}
                                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-50 dark:hover:bg-gray-800"
                                      role="menuitem" tabindex="-1"
                                      id="user-menu-item-0">Your Profile
                                </Link>

                                <Link href={route('publicLinks.index')}
                                      on:click={() => {showProfileDropdown = false; closeSidebar()}}
                                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-50 dark:hover:bg-gray-800"
                                      role="menuitem" tabindex="-1"
                                      id="user-menu-item-1">Shared Groups
                                </Link>

                                <Link href={route('api-tokens.index')}
                                      on:click={() => {showProfileDropdown = false; closeSidebar()}}
                                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-50 dark:hover:bg-gray-800"
                                      role="menuitem" tabindex="-1"
                                      id="user-menu-item-1">API Tokens
                                </Link>
                            </InnerDropdownSection>

                            <!-- Theme toggle -->
                            <InnerDropdownSection>
                                {#if theme === 'dark'}
                                    <DropdownItem on:clicked={() => (theme = changeTheme('light'))} title="Dark Theme">
                                        <Moon slot="icon"/>
                                    </DropdownItem>

                                {:else if theme === 'light'}
                                    <DropdownItem on:clicked={() => (theme = changeTheme())} title="Light Theme">
                                        <Sun slot="icon"/>
                                    </DropdownItem>

                                {:else}
                                    <DropdownItem on:clicked={() => (theme = changeTheme('dark'))} title="System Theme">
                                        <ComputerDesktop slot="icon"/>
                                    </DropdownItem>
                                {/if}
                            </InnerDropdownSection>

                            <InnerDropdownSection>
                                <button use:inertia={{ href: route('logout'), method: 'post' }}
                                        on:click={() => {showProfileDropdown = false; closeSidebar()}}
                                        type="button"
                                        class="flex items-center px-4 py-2 w-full text-sm text-gray-700 group hover:text-red-700 hover:bg-gray-100 dark:text-gray-50 dark:hover:bg-gray-800 dark:hover:text-red-400"
                                        role="menuitem" tabindex="-1" id="user-menu-item-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                         class="mr-3 size-5 text-gray-500 group-hover:text-red-500 dark:text-gray-400">
                                        <path fill-rule="evenodd"
                                              d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z"
                                              clip-rule="evenodd"/>
                                        <path fill-rule="evenodd"
                                              d="M19 10a.75.75 0 0 0-.75-.75H8.704l1.048-.943a.75.75 0 1 0-1.004-1.114l-2.5 2.25a.75.75 0 0 0 0 1.114l2.5 2.25a.75.75 0 1 0 1.004-1.114l-1.048-.943h9.546A.75.75 0 0 0 19 10Z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    Log Out
                                </button>
                            </InnerDropdownSection>

                            {#if showAppVersion}
                                <InnerDropdownSection>
                                    <a href={`https://github.com/beromir/Servas/releases/tag/v${appVersion}`}
                                       target="_blank"
                                       rel="noreferrer noopener nofollow"
                                       class="block px-4 py-1 w-full group text-gray-600 text-xs hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                                        v{appVersion}
                                    </a>
                                </InnerDropdownSection>
                            {/if}
                        </Dropdown>
                    </div>

                    <!-- Search button -->
                    <button on:click={() => dispatchCustomEvent('showCommandPalette')} title="Open search bar"
                            type="button"
                            class="ml-auto py-1.5 px-2 text-gray-200 rounded-md transition hover:text-white hover:bg-gray-600 hover:ring-1 hover:ring-gray-500 sm:ml-1.5 dark:hover:bg-gray-700">
                        <span class="sr-only">Open search bar</span>
                        <!-- Heroicon name: outline/search -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>

                <nav class="flex-grow px-4 pb-4 h-dvh overflow-y-auto md:pt-2">
                    <ul class="space-y-2">
                        <li>
                            <MenuItem title="Home" url={route('links.index')}>
                                <Home/>
                            </MenuItem>
                        </li>
                        <li>
                            <MenuItem title="Inbox" url={route('inbox.index')} exactMatch={false}>
                                <Inbox/>
                            </MenuItem>
                        </li>
                        <li>
                            <MenuItem title="Tags" url={route('tags.index')}>
                                <Tag/>
                            </MenuItem>
                        </li>
                        <li class="!mt-4 sm:hidden">
                            <MenuButton on:clicked={() => dispatchCustomEvent('prepareCreateNewLink')} title="New link">
                                <Plus/>
                            </MenuButton>
                        </li>
                    </ul>

                    <div class="flex justify-between items-center mt-6 md:mt-8">
                        <div class="text-xs text-gray-200 font-semibold uppercase">Groups</div>

                        <button on:click={() => dispatchCustomEvent('createGroup', $page.props.group?.id)} type="button"
                                class="rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 class="size-6 fill-gray-100 hover:fill-white">
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
                </nav>
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
                        class="py-1.5 px-2 bg-white shadow-sm rounded-md ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:ring-contrast dark:hover:bg-gray-700">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                         class="size-6 text-gray-600 transition duration-300 delay-150 dark:text-gray-100">
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
<DeletePublicLinkModal/>
