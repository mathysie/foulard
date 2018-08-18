{if !empty($errors)}
    <div class="alert alert-danger">
        <p>Er zijn errors opgetreden:</p>
        <ul>
            {foreach $errors as $error}
            {if is_array($error)}
            {foreach $error as $suberror}
            <li>{$suberror|escape}</li>
            {/foreach}
            {else}
            <li>{$error|escape}</li>
            {/if}
            {/foreach}
        </ul>
    </div>
{/if}
