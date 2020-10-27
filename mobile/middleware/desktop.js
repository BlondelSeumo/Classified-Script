export default function (context) {

	if (context.isDesktop) {
		context.redirect(process.env.DESKTOP_URL)
	}
}
