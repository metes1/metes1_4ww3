Seda Mete
metes1
400134659

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
	1) It can help reduce bandwidth overhead. A high resolution picture is usually uneccessary if the image is being shown on a small mobile phone. A lower resolution picture would work just as fine.These tags allow you to use lower resolution over higher, when higher quality isn't needed.

	2) It can help improve user experience as it speeds up performance. A website will likely load a bit faster in some instances.

	3) It also improves user experience by adapting image to better fit different sized displays screens. Overall, the visual experience of the user will be improved.


iii. A negative aspect about using HTML5 <picture> and <source> attributes is that they are not support by all browsers, especially older ones. This negative can be mitigated by using the <img> tag inside of <picture> as a fall back in case that the browser doesn't support <picture>.
	