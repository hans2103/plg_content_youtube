/* eslint-disable */

(function () {
	/**
	 * Wait until document is loaded
	 */
	document.addEventListener('DOMContentLoaded', function () {

		let YouTubeContainers = document.querySelectorAll('.embed-youtube');

		// Iterate over every YouTube container you may have
		for (let i = 0; i < YouTubeContainers.length; i++) {
			let container = YouTubeContainers[i];
			//let imageSource = "https://img.youtube.com/vi/" + container.dataset.videoId + "/sddefault.jpg";
			let imageSrc = container.dataset.videoSrc;
			let imageAlt = container.dataset.videoAlt;
			let imageSrcset = container.dataset.videoSrcset;

			// Load the Thumbnail Image asynchronously
			let image = new Image();
			image.src = imageSrc;
			image.alt = imageAlt;
			image.srcset = imageSrcset;
			image.loading = 'lazy';
			image.setAttribute('data-sizes', 'auto');
			image.width = '1078';
			image.sizes = '648px';

			container.appendChild(image);
			// image.addEventListener('load', function () {
			// 	container.appendChild(image);
			// });

			// When the user clicks on the container, load the embedded YouTube video
			container.addEventListener('click', function () {
				let iframe = document.createElement('iframe');

				iframe.setAttribute('frameborder', '0');
				iframe.setAttribute('allowfullscreen', '');
				iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
				// Important: add the autoplay GET parameter, otherwise the user would need to click over the YouTube video again to play it
				iframe.setAttribute('loading', 'lazy');
				iframe.setAttribute('src', 'https://www.youtube.com/embed/' + this.dataset.videoId + '?disablekb=1&rel=0&showinfo=0&autoplay=1');

				// Clear Thumbnail and load the YouTube iframe
				this.innerHTML = '';
				this.appendChild(iframe);
			});
		}

	});

})();

/* eslint-enable */
