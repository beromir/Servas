<script>
    import WidgetContainer from "@/Components/Widgets/WidgetContainer.svelte";
    import Widget from "@/Components/Widgets/Widget.svelte";
    import {route} from "@/utils";
    import {inertia} from "@inertiajs/svelte";

    export let stats = {};
</script>

<WidgetContainer>
    <Widget title="Stats" withBackground={false}>
        <dl class="grid grid-cols-3 gap-3 sm:gap-5">
            <a href={route('links.index')} use:inertia
               class="px-4 py-5 bg-primary-100 shadow rounded-lg overflow-hidden sm:p-6">
                <dt class="text-sm font-medium text-primary-600 truncate">Links</dt>
                <dd class="mt-1 text-xl font-semibold text-primary-800">{stats.links}</dd>
            </a>

            <a href={route('groups.index')} use:inertia
               class="px-4 py-5 bg-primary-100 shadow rounded-lg overflow-hidden sm:p-6">
                <dt class="text-sm font-medium text-primary-600 truncate">Groups</dt>
                <dd class="mt-1 text-xl font-semibold text-primary-800">{stats.groups}</dd>
            </a>

            <a href={route('tags.index')} use:inertia
               class="px-4 py-5 bg-primary-100 shadow rounded-lg overflow-hidden sm:p-6">
                <dt class="text-sm font-medium text-primary-600 truncate">Tags</dt>
                <dd class="mt-1 text-xl font-semibold text-primary-800">{stats.tags}</dd>
            </a>
        </dl>
    </Widget>
</WidgetContainer>
