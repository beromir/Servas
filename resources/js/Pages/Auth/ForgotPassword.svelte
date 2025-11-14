<script module>
    import GuestLayout, {title} from "@/Layouts/GuestLayout.svelte";

    export const layout = GuestLayout;
</script>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.svelte';
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.svelte';
    import JetButton from '@/Jetstream/Button.svelte';
    import JetLabel from '@/Jetstream/Label.svelte';
    import JetValidationErrors from '@/Jetstream/ValidationErrors.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";
    import Input from "@/Components/Input.svelte";

    $title = 'Forgot Password';

    let status = '';

    let form = useForm({
        email: '',
    });

    function submit(event) {
        event.preventDefault();

        $form.post(route('password.email'));
    }
</script>

<JetAuthenticationCard>
    {#snippet logo()}
        <JetAuthenticationCardLogo/>
    {/snippet}

    <div class="mb-4 text-sm text-gray-600">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset
        link that will allow you to choose a new one.
    </div>

    {#if status}
        <div class="mb-4 font-medium text-sm text-green-600">
            {status}
        </div>
    {/if}

    <JetValidationErrors hasErrors={$form.hasErrors} class="mb-4"/>

    <form onsubmit={submit}>
        <div>
            <JetLabel id="email" label="Email"/>
            <Input id="email" type="email" required autofocus bind:value={$form.email} class="mt-1"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <JetButton class={$form.processing ? 'opacity-25' : ''}
                       disabled={$form.processing} type="submit">
                Email Password Reset Link
            </JetButton>
        </div>
    </form>
</JetAuthenticationCard>
