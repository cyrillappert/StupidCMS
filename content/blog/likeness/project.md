---
title: "Likeness Bot"
published: true
date: 2025-06-16
featured_img:
  type: img
  src: mylien-03.png
  alt: "Recursive image search visualization"
tags:
  - Work
  - Python
  - Technology
---

## Likeness

Built a Python Selenium bot for Thi My Lien Nguyen's art project. The bot does reverse image searches recursively - finds a similar image, then searches for that image, creating a chain of visual associations.

![large:Bot interface showing search progression](mylien-01.png)

Started with one image, used reverse image search to find similar ones, then took the first result and searched again. Repeat until you have a chain of images connected by algorithmic similarity.

### Technical Implementation

Used Selenium to automate Google Images reverse search. Simple loop that captures results and feeds them back into the search.

![medium:Search results chain visualization](mylien-05.png)

![small:Search results chain visualization](mylien-03.png)

The recursive nature creates unexpected visual journeys. What starts as a portrait might end up as abstract art through the algorithm's interpretation of similarity.
