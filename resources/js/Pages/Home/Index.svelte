<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import Stats from "@/Widgets/Stats.svelte";
    import Later from "@/Widgets/Later.svelte";
    import LastAdded from "@/Widgets/LastAdded.svelte";
    import WidgetContainer from "@/Components/Widgets/WidgetContainer.svelte";

    export let stats = {};
    export let later = {};
    export let lastAdded = {};

    $title = 'Home';
</script>

<Main>
    <!-- Widgets -->
    <div class="space-y-8">
        <Stats {stats}/>
        <WidgetContainer hasColumns={true}>
            <Later {later}/>
            <LastAdded {lastAdded}/>
        </WidgetContainer>
    </div>
</Main>
