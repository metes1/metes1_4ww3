Name: Seda Mete
MAC ID: metes1
Student Number: 400134659

All page links:
1. http://18.222.242.154/home/registration.html
2. http://18.222.242.154/home/search.html
3. http://18.222.242.154/home/results_sample.html
4. http://18.222.242.154/home/individual_sample.html
5. http://18.222.242.154/home/submission.html


I did both add-on tasks 1 and 2.

Add-on task 2: HTML5 images and video
2.d
i.

The two versions of graphics explained in 2.a are about a lower resolution (standard) and a higher resolution (for high-DPI displays) version
of an image. The standard version will have less pixels than the high resolution one.

Sample code: 
    <picture class="mainimage">
        <source media="(min-width: 800px)" srcset="images/bookstoreshelves.jpg">
        <source media="(min-width: 450px)" srcset="images/bookstoreshelvesSmall.jpg">
        <img class="mainimage" src="images/bookstoreshelves.jpg" alt="bookstore" itemprop= "mainimage">
    </picture>

In this example the <picture> element contains three other elements within it. The <picture> element will pick the most suitable image among those three. The <source> element that specifies and refers to the actual media file. The <img> element refers to the default image that will appear if none of the sources are suitable for the the device or if the browser doesn't support <picture> elements.


ii. Three positive goals that can be achieved using HTML5 <picture> and <source> attributes are:
	1) It can help reduce bandwidth overhead. A high resolution picture is usually uneccessary if the image is being shown on a small mobile phone. A lower resolution picture would work just as fine. These tags allow you to use lower resolution over higher, when higher quality isn't needed. High resolution images take longer to load, so if we don't need them we should avoid it.

	2) Makes responsive website design easier for developers and improves the overall final result of a website.

	3) It improves user experience both visually and performance wise. Firstly, it adapts images to better fit different sized display screens, making the site look more appealing. Overall, the visual experience of the user will be improved. It can also help improve user experience as it can potentially speed up performance of the website (mentioned in 1).


iii. A negative aspect about using HTML5 <picture> and <source> attributes is that they are not support by all browsers, especially older ones. This negative can be mitigated by using the <img> tag inside of <picture> as a fall back in case that the browser doesn't support <picture>.



Notes to Marker:

Currently the navigation bar goes underneath the website title when the screen is smaller. I styled it so it would adapt well to the screen size. Initially I wanted to use a hamburger style menu for the mobile display because I think it looks nicer, but I didn't because it requires javascript (which is not allowed as stated in the project specification). I may potentially change the mobile navigation bar in the future to hamburger style menu.

I did not add index.html as it was not in the project specification. It will be added later in the future as a homepage. I provided specific links to every page on my server, so they can be access that way instead (since there's no homepage). The pages are also accessible by navigating through the site. For instance, through the navigation bar you can access search.html, submission.html and registration.html. results_sample.html can be accessed from the Search page by clicking the search button. Then individual_sample.html can be accessed through the results_sample.html page by clicking the "4. King W. Books" result.
