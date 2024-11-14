<script context="module">
    import GuestLayout, {title} from "@/Layouts/GuestLayout.svelte";

    export const layout = GuestLayout;
</script>

<script>
    import {inertia, useForm} from '@inertiajs/svelte';
    import AuthenticationCard from '@/Components/Auth/AuthenticationCard.svelte'
    import {route} from '@/utils';
    import Text from "@/Components/FormLayouts/Inputs/Text.svelte";
    import SubmitButton from "@/Components/Auth/SubmitButton.svelte";
    import Checkbox from "@/Components/FormLayouts/Inputs/Checkbox.svelte";

    export let canResetPassword;

    $title = 'Log in';

    let form = useForm('Login', {
        email: null,
        password: null,
        remember: true,
    });

    function login() {
        $form.post(route('login'), {
            onSuccess: () => $form.reset('password'),
        });
    }
</script>

<AuthenticationCard>
    <form on:submit|preventDefault={login}>
        <div class="space-y-6">
            <Text name="email" label="Email address" type="email" bind:value={$form.email} error={$form.errors.email}
                  required
                  autofocus/>

            <Text name="password" label="Password" type="password" bind:value={$form.password}
                  error={$form.errors.password} required
                  autocomplete="current-password"/>

            <Checkbox name="remember" label="Remember me" bind:checked={$form.remember}/>
        </div>

        {#if canResetPassword}
            <a href={route('password.request')} use:inertia
               class="underline text-sm text-gray-600 hover:text-gray-800 dark:text-gray-200 dark:hover:text-white">
                Forgot your password?
            </a>
        {/if}

        <SubmitButton title="Log in" class="mt-12"/>
    </form>
</AuthenticationCard>
