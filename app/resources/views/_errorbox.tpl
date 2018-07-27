{if !empty($errors)}
    <div class="alert alert-danger">
        <p>Er zijn errors opgetreden:</p>
        <ul>
            {foreach $errors as $error}
            <li>{$error|escape}</li>
            {/foreach}
        </ul>
    </div>
{/if}
