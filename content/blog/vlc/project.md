---
title: "VLC Media Player"
published: true
date: 2026-06-16
featured_img:
  type: img
  src: artasio-02.png
  alt: "Video box at Hasliberg"
tags:
  - Work
  - Raspberry
  - Python
---

## Alpine Media Player

![medium:Hasliberg Mountain View](artasio-03.png)

![small:Hasliberg Mountain View](artasio-02.png)

Built a media player for outdoor use at Hasliberg. Needed something that would just work in alpine conditions without babysitting.

### Setup

Raspberry Pi with GPIO buttons and LEDs. VLC handles the video playback. Pretty straightforward:

```python
button.wait_for_press()
led_green.off() 
led_blue.on()

for video_file in video_files:
    media = vlc.Media(video_file)
    player.set_media(media)
    player.play()
```

Green LED when idle, blue when playing. Button press cycles through videos on the USB stick.

### Implementation

- Soldered the button and LED connections directly to GPIO pins
- Weather-resistant housing for the Pi
- Loops through all video files automatically
- Falls back to black screen when done

No overcomplicated interface. No network dependencies. Just press button, watch videos.

The thing's been running outdoors for months now. Does what it's supposed to do.

[Full Code](https://github.com/cyrillappert/vlc-player)