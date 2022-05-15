<script context="module">
    import GuestLayout, {title} from "@/Layouts/GuestLayout.svelte";

    export const layout = GuestLayout;
</script>

<script>
    import {inertia, useForm} from '@inertiajs/inertia-svelte';
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.svelte'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.svelte'
    import JetButton from '@/Jetstream/Button.svelte'
    import JetInput from '@/Jetstream/Input.svelte'
    import JetCheckbox from '@/Jetstream/Checkbox.svelte'
    import JetLabel from '@/Jetstream/Label.svelte'
    import ValidationErrors from "@/Jetstream/ValidationErrors.svelte";
    import {route} from '@/utils';

    export let canResetPassword;

    $title = 'Log in';

    let form = useForm('Login', {
        email: null,
        password: null,
        remember: true,
    });

    function login() {
        $form.post('/login', {
            onSuccess: () => $form.reset('password'),
        });
    }
</script>

<JetAuthenticationCard>
    <JetAuthenticationCardLogo slot="logo"/>

    <ValidationErrors class="mt-4" errors={$form.errors} hasErrors={$form.hasErrors}/>

    <form on:submit|preventDefault={login}>
        <div>
            <JetLabel id="email" label="Email"/>
            <JetInput bind:value={$form.email} id="email" type="email" class="mt-1 block w-full" required autofocus/>
            {#if $form.errors.email}{$form.errors.email}{/if}
        </div>

        <div class="mt-4">
            <JetLabel id="password" label="Password"/>
            <JetInput bind:value={$form.password} id="password" type="password" class="mt-1 block w-full" required
                      autocomplete="current-password"/>
        </div>

        <div class="block mt-4">
            <label for="remember" class="flex items-center">
                <JetCheckbox id="remember" name="remember" bind:checked={$form.remember}/>
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            {#if canResetPassword}
                <a href={route('password.request')} use:inertia
                   class="underline text-sm text-gray-600 hover:text-gray-900">
                    Forgot your password?
                </a>
            {/if}

            <JetButton class="ml-4" type="submit">
                Log in
            </JetButton>
        </div>
    </form>
</JetAuthenticationCard>
