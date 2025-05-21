export function getMimeType(filename) {
	const ext = filename.split(".").pop().toLowerCase();
	switch (ext) {
		case "png":
			return "image/png";
		case "jpg":
		case "jpeg":
			return "image/jpeg";
		case "gif":
			return "image/gif";
		case "pdf":
			return "application/pdf";
		default:
			return "application/octet-stream";
	}
}
