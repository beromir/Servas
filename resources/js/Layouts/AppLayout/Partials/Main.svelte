<script>
    import Button from "@/Components/Buttons/Button.svelte";
    import {dispatchCustomEvent} from "@/utils";

    export let title = '';
    export let showNewLinkButton = true;
</script>

<div class="max-w-7xl mx-auto pb-12 sm:pt-4 sm:px-6 lg:px-8">

    {#if title}
        <div
            class="flex p-4 w-full justify-between items-center bg-white shadow sm:px-0 sm:pt-0 sm:bg-transparent sm:border-b-2 sm:border-gray-300 sm:shadow-none dark:bg-gray-900 dark:border-gray-700">
            {#if title}
                <h1 class="text-3xl text-gray-900 font-bold dark:text-white">{title}</h1>
            {/if}
            <slot name="toolbar">
                <div></div>
            </slot>

            {#if showNewLinkButton}
                <div class="hidden space-x-3 sm:inline-flex sm:ml-6">
                    <Button on:clicked="{() => dispatchCustomEvent('prepareCreateNewLink')}" title="new link"
                            class="focus:ring-offset-gray-100">
                        <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </Button>
                </div>
            {/if}
        </div>
    {/if}

    <div class="py-6">
        <!-- Main content -->
        <slot/>
    </div>
</div>
