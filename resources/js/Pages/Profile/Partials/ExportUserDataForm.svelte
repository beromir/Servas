<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import {route} from "@/utils";
    import RadioGroup from "@/Components/FormLayouts/Inputs/RadioGroup.svelte";
    import Radio from "@/Components/FormLayouts/Inputs/Radio.svelte";

    let formSection = $state();
    let csrfToken = $state('');

    async function exportUserData() {
        await setCsrfToken();

        formSection.submit();
    }

    function setCsrfToken() {
        return axios.get(route('csrf-token'))
            .then(response => csrfToken = response.data);
    }
</script>

<JetFormSection submitted={exportUserData} bind:this={formSection} method="post" action={route('export')}>
    {#snippet title()}
    
            Export Data
        
    {/snippet}

    {#snippet description()}
    
            Export your account data as JSON or HTML.
        
    {/snippet}

    {#snippet form()}
    
            <input type="hidden" name="_token" bind:value={csrfToken}/>

            <div class="col-span-full">
                <RadioGroup label="Export format">
                    <Radio id="export-json" name="exportFormat" value="json" label="JSON (Servas export)" checked/>
                    <Radio id="export-html" name="exportFormat" value="html" label="HTML (Browser bookmarks)"/>
                </RadioGroup>
            </div>
        
    {/snippet}

    {#snippet actions()}
    
            <JetButton type="submit">
                Export
            </JetButton>
        
    {/snippet}
</JetFormSection>
