<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import {route} from "@/utils";
    import RadioGroup from "@/Components/FormLayouts/Inputs/RadioGroup.svelte";
    import Radio from "@/Components/FormLayouts/Inputs/Radio.svelte";

    let formSection;
    let csrfToken = '';

    async function exportUserData() {
        await setCsrfToken();

        formSection.submit();
    }

    function setCsrfToken() {
        return axios.get(route('csrf-token'))
            .then(response => csrfToken = response.data);
    }
</script>

<JetFormSection on:submitted={exportUserData} bind:this={formSection} method="post" action={route('export')}>
    <svelte:fragment slot="title">
        Export Data
    </svelte:fragment>

    <svelte:fragment slot="description">
        Export your account data as JSON or HTML.
    </svelte:fragment>

    <svelte:fragment slot="form">
        <input type="hidden" name="_token" bind:value={csrfToken}/>

        <div class="col-span-full">
            <RadioGroup label="Export format">
                <Radio id="export-json" name="exportFormat" value="json" label="JSON (Servas export)" checked/>
                <Radio id="export-html" name="exportFormat" value="html" label="HTML (Browser bookmarks)"/>
            </RadioGroup>
        </div>
    </svelte:fragment>

    <svelte:fragment slot="actions">
        <JetButton type="submit">
            Export
        </JetButton>
    </svelte:fragment>
</JetFormSection>
