export default function ({ store, redirect }) {
	// Check if user online	
	if (!store.state.auth.loggedIn) {
		return redirect('/')
	}

	// Get 
	const role = store.state.auth.user.role.group

    // Check if admin or owner
	if (role === "administrator" ||  role === "owner") {
        return true
    }else if (store.state.auth.user.subscription && !store.state.auth.user.subscription.isExpired) {
        return true
    }else{
        return redirect('/')
    }
}
