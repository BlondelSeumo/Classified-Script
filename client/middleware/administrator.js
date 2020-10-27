export default function ({ store, redirect }) {
	
	// Check if user online	
	if (!store.state.auth.loggedIn) {
		return redirect('/auth/login')
	}

	// Get role
	const role = store.state.auth.user.role.group

	if (role === "administrator" ||  role === "owner") {
  		//return redirect('/')
  	}else{
  		redirect('/')
  	}
}
