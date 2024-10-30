var clouds = Class.create({
	/**
	 * Max cloud container height
	 */
	cloudContainerHeight: 389,
	
	/**
	 * Cloud Information object
	 */
	cloudInfo: {},
	
	/**
	 * Minimum cloud speed
	 */
	cloudMinSpeed: 100,
	
	/**
	 * Maximum cloud speed - Max of 900 though so they fall behind the site elements
	 */
	cloudMaxSpeed: 300,

	/**
	 * A bit of cloud randomness thrown into the mixer
	 */
	cloudDelayRandomNess: 50,
	
	/**
	 * Is random delay turned on
	 */
	cloudRandomDelay: false,
	
	/**
	 * How wide is our cloud container?
	 */
	cloudContainerWidth: 0,
	
	/**
	 * Are we animating clouds
	 */
	cloudAnimation: false,
	
	/**
	 * Are we randomizing the position of the clouds
	 */
	cloudRandomization: true,
	
	/**
	 * Automatic initialization function, finds all clouds and starts them moving
	 * @param {boolean} cloudAnimation Are we moving clouds?
	 * @return void
	 */
	initialize: function (cloudAnimation, minSpeed, maxSpeed)
	{
		this.cloudAnimation = cloudAnimation;
		this.cloudMinSpeed = minSpeed;
		this.cloudMaxSpeed = maxSpeed;
		
		// Find all clouds and record them
		this.cloudInfo = $$('div.cloud');
		
		// Get cloud width - use body tag
		this.cloudContainerWidth = document.body.getWidth();
		
		if(this.cloudRandomization == true)
		{
			this.randomize();
		}
		if(this.cloudAnimation == true)
		{
			this.animate();
		}
	},
	
	/**
	 * Move the clouds!
	 * @param {element} cloud The cloud element we want to move
	 * @return void
	 */
	moveClouds: function(cloud)
	{
		this.cloudInfo.each(function(e, index)
		{
			// Create a random speed
			var randomSpeed = (Math.floor(Math.random() * (this.cloudMaxSpeed - this.cloudMinSpeed))) + this.cloudMinSpeed;

			// Set the z-indexing of the cloud depending on its speed, the faster the higher!
			e.setStyle({zIndex: 500-(randomSpeed)});
			
			// Store the cloud information
			e.speed = randomSpeed;
			e.delay = Math.floor(Math.random() * this.cloudDelayRandomNess);
			
			// Now move it!
			this.moveCloudAction(e, index);
		}.bind(this));
	},
	
	/**
	 * Actual move cloud action
	 * @param {element} cloud The cloud element we want to move
	 */
	moveCloudAction: function(cloud, index)
	{
		// Have we got random delay turned on?
		if(this.cloudRandomDelay == true)
		{
			var cloudDelay = this.cloudInfo[index].delay;
		} else
		{
			var cloudDelay = 0;
		}
		
		// Setup effect options
		this.moveOptions = {
			x: (0 - (cloud.getWidth() * 2)),
			y: cloud.getStyle('top').replace('px', ''),
			mode: 'absolute',
			transition: Effect.Transitions.linear,
			duration: this.cloudInfo[index].speed,
			afterFinish: this.resetClouds.bind(this, cloud, index),
			delay: cloudDelay
		};
		
		// Start the cloud moving
		new Effect.Move(cloud, this.moveOptions);
	},
	
	/**
	 * Reset a cloud position
	 * @param {object} cloud An individual cloud element
	 * @param {integer} index The cloud index number
	 */
	resetClouds: function(cloud, index)
	{
		// Reset its position
		cloud.setStyle({ 
			left : (this.cloudContainerWidth+cloud.getWidth()+50)+'px', 
			top: Math.floor(Math.random() * this.cloudContainerHeight)+'px'
		});
		
		// Reset the delay
		this.cloudInfo[index].delay = 0;
		
		// Don't stop, move it move it !!
		this.moveCloudAction(cloud, index);
	},
	
	/**
	 * Animate our clouds
	 */
	animate: function()
	{
		// Move the clouds
		this.moveClouds();
	},
	
	/**
	 * Randomize the position of the clouds
	 * @return void
	 */
	randomize: function()
	{
		// Loop through all clouds we've previously found and ramdomize its position
		this.cloudInfo.each(function(e){
			// Use the next line if we're using static clouds
			e.setStyle({left:((Math.floor(Math.random() * ((this.cloudContainerWidth * 2) + -500))) - 500)+'px'});
			
			e.appear({
				duration: 2, 
				delay: Math.floor(Math.random() * 3)
			});

		}.bind(this));
	}
});