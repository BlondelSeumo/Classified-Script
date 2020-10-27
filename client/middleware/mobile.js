export default function (context) {

	if (context.isMobile || context.isTablet) {
		context.redirect(process.env.MOBILE_URL)
	}
}
