<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import FormSection from "@/Components/FormLayouts/Settings/FormSection.svelte";
    import Textarea from "@/Components/FormLayouts/Settings/Inputs/Textarea.svelte";
    import Text from "@/Components/FormLayouts/Settings/Inputs/Text.svelte";
    import {useForm, router} from "@inertiajs/svelte";
    import {route} from "@/utils";
    import Button from "@/Components/Buttons/Button.svelte";

    $: $title = 'Create Dashboard';

    let form = useForm({
        title: null,
        description: null,
    });

    function createDashboard() {
        $form.post(route('dashboards.store'), {
            preserveScroll: true,
            onSuccess: () => {
                $form.reset();
            },
        });
    }
</script>

<Main>
    <form on:submit|preventDefault={createDashboard} class="space-y-6">
        <FormSection title="General">
            <svelte:fragment slot="description">
                Provide some information for the dashboard.
            </svelte:fragment>

            <Text name="title" label="Title" bind:value={$form.title} error={$form.errors.title}/>
            <Textarea name="description" label="Description" bind:value={$form.description}
                      error={$form.errors.description}/>
        </FormSection>

        <div class="flex justify-end">
            <Button on:clicked={() => router.visit(route('dashboards.index'))} title="Cancel" color="white"
                    class="w-auto"/>
            <Button title="Create" type="submit" class="ml-3 w-auto"/>
        </div>
    </form>
</Main>
