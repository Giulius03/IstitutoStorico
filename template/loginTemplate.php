<h1 class="text-center fs-1 fw-bold my-4">Accedi</h1>
<form class="mx-5" onsubmit="login(event)">
    <ul class="loginForm list-unstyled px-4">
        <li class="form-floating my-4">
            <input name="username" type="text" class="form-control" id="username" placeholder="username" required />
            <label for="username">Username</label>
        </li>
        <li class="input-group mb-4">
            <div class="form-floating">
                <input name="password" type="password" class="form-control flex-grow-1" id="password" placeholder="Password" required />
                <label for="password">Password</label>
            </div>
            <span class="input-group-text bi bi-eye-fill" id="btnSeePw"></span>
        </li>
        <li class="text-center mb-4">
            <input class="btn btn-dark w-75" type="submit" id="btnLog" value="Entra" />
        </li>
        <li id="loginError" class="text-center mb-4">

        </li>
    </ul>
</form>