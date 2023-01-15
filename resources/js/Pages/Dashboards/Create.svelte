<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import FormSection from "@/Components/FormLayouts/Settings/FormSection.svelte";
    import Textarea from "@/Components/FormLayouts/Settings/Inputs/Textarea.svelte";
    import Text from "@/Components/FormLayouts/Settings/Inputs/Text.svelte";

    $: $title = 'Create Dashboard';
</script>

<Main>
    <form class="space-y-6" action="#" method="POST">
        <FormSection title="General">
            <svelte:fragment slot="description">
                Provide some information for the dashboard.
            </svelte:fragment>

            <Text name="title" label="Title"/>
            <Textarea name="description" label="Description" placeholder="Test"/>
        </FormSection>

        <div class="flex justify-end">
            <button type="button"
                    class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Cancel
            </button>
            <button type="submit"
                    class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Save
            </button>
        </div>
    </form>
</Main>
