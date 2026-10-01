@extends('layouts.web')
@section('title', $package_details->meta_title.' |  Phoenix Tours & Travels')
@section('css')
<style>
    .sub-menu-new{
       text-align: center;
        background-color: #1fb1d7;
        margin-bottom: 30px;
        padding: 11px 0px;
    }
    .sub-menu-new li{
        display: inline-block;
    }
    .sub-menu-new li a{
        padding: 14px 10px;
        color: #FFF;
    }
    .me-2-day {
            width: 60px;
            height: 26px;
            text-align: center;
            line-height: 26px;
            background: #4c82ff24;
            margin-right: 15px;
            font-weight: 400;
            font-size: 11px;
            border-radius: 10%;
            font-weight: bold;
    }
    /* Fix for Tour Details Sidebar */
    .details-info {
        border-bottom: 1px solid #e9ecef;
        padding: 6px 0;
    }
    .details-info:last-of-type {
        border-bottom: none;
    }
    .details-info h6 {
        min-width: 120px;
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 0;
    }
    .details-info p {
        margin-bottom: 0;
        color: #212529;
        font-weight: 500;
    }
    .places-covered-text {
        color: #ff9d5b !important;
        font-weight: 600;
        padding: 8px 0 0 0;
        line-height: 1.5;
    }
    .tour-detail-header {
        display: flex;
        align-items: center;
        /*margin-bottom: 16px;*/
    }
    .tour-detail-header .avatar {
        margin-right: 10px;
    }
    
   .col-xl-35{
    flex:0 0 29.166667%;
    max-width:29.166667%;
}

.custom-mb {
    margin-bottom: 2.5rem !important;
}

.image-container {
    overflow: hidden;
    border-radius: 8px;
}

.image-rounded {
    border-radius: 8px !important;
}

.image-label {
    /*background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);*/
    padding: 30px 15px 15px 15px;
    border-radius: 0 0 8px 8px;
    width: 100%;
    left: 0;
    bottom: 0;
}

.image-label .badge {
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 0.5px;
    backdrop-filter: blur(4px);
}

.image-label-small {
    /*background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);*/
    padding: 20px 10px 10px 10px;
    border-radius: 0 0 8px 8px;
    width: 100%;
    left: 0;
    bottom: 0;
}

.image-label-small .badge {
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 0.3px;
    backdrop-filter: blur(2px);
}

.activitys-img {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.activitys-img:hover {
    transform: scale(1.02);
    z-index: 1;
}

.activitys-img .galley-wrap {
    display: block;
    overflow: hidden;
}

.activity-wrap .badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

/* Hover effect on images */
.image-container:hover .image-label .badge,
.image-container:hover .image-label-small .badge {
    background-color: #0d6efd !important;
    transform: scale(1.05);
    transition: all 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .image-label .badge {
        font-size: 12px;
        padding: 5px 12px;
    }
    
    .image-label-small .badge {
        font-size: 9px;
        padding: 3px 8px;
    }
}
.galley-wrap {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
}


/* Package Highlight Styles */
.package-highlight-section {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.highlight-header {
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 15px;
}

.highlight-title {
    font-weight: 600;
    color: #1a1a2e;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.view-all-link {
    color: #0d6efd;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
}

.view-all-link:hover {
    color: #0a58ca;
    transform: translateX(3px);
}

.image-container {
    overflow: hidden;
    border-radius: 10px;
    position: relative;
}

.image-rounded {
    border-radius: 10px !important;
}

/* Main Image Overlay Content */
.image-overlay-content {
    background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
    width: 100%;
    padding: 30px 20px 20px 20px !important;
    border-radius: 0 0 10px 10px;
}

.image-overlay-content h5 {
    font-weight: 600;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.image-overlay-content p {
    text-shadow: 0 1px 3px rgba(0,0,0,0.3);
}

/* Image Labels */
.image-label {
    background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    padding: 30px 15px 15px 15px;
    border-radius: 0 0 10px 10px;
    width: 100%;
    left: 0;
    bottom: 0;
}

.image-label .badge {
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.5px;
    backdrop-filter: blur(4px);
    background: rgba(13, 110, 253, 0.85) !important;
}

.image-label-small {
    background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
    padding: 20px 10px 10px 10px;
    border-radius: 0 0 10px 10px;
    width: 100%;
    left: 0;
    bottom: 0;
}

.image-label-small .badge {
    font-size: 9px;
    font-weight: 500;
    letter-spacing: 0.3px;
    backdrop-filter: blur(2px);
    background: rgba(13, 110, 253, 0.85) !important;
}

.activitys-img {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.activitys-img:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    z-index: 2;
}

.activitys-img .galley-wrap {
    display: block;
    overflow: hidden;
}

/* Video Wrapper */
.video-wrapper {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.video-wrapper iframe {
    border-radius: 10px;
}

/* Highlight Info Card */
.highlight-info-card {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.highlight-info-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.highlight-info-card .badge {
    font-size: 11px;
    padding: 5px 10px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Property Label */
.property-label {
    padding: 10px 0;
    border-top: 1px dashed #dee2e6;
}

.property-label a {
    transition: all 0.3s ease;
}

.property-label a:hover {
    color: #0a58ca !important;
}

/* Badge Icons */
.badge ion-icon {
    font-size: 14px;
    margin-right: 3px;
}

/* Responsive */
@media (max-width: 992px) {
    .highlight-title {
        font-size: 1.2rem;
    }
    
    .image-overlay-content h5 {
        font-size: 1rem;
    }
    
    .image-overlay-content p {
        font-size: 0.85rem;
    }
}

@media (max-width: 768px) {
    .highlight-header {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 10px;
    }
    
    .view-all-link {
        font-size: 13px;
    }
    
    .image-overlay-content {
        padding: 20px 15px 15px 15px !important;
    }
    
    .image-overlay-content h5 {
        font-size: 0.9rem;
    }
    
    .image-label .badge {
        font-size: 11px;
        padding: 4px 10px;
    }
    
    .highlight-info-card {
        margin-top: 15px;
    }
}
</style>
<style>
    :root {
      --reference-image: url("");
      --blue: #0097ff;
      --ink: #101010;
      --muted: #64666b;
      --cream: #fff1dc;
      --border: #e8e9ec;
    }

    button { font: inherit; }
    button, a { -webkit-tap-highlight-color: transparent; }
    button { cursor: pointer; }
    button:focus-visible, summary:focus-visible {
      outline: 3px solid var(--blue);
      outline-offset: 4px;
    }
    button:disabled { cursor: default; }
    svg { display: block; flex-shrink: 0; }
    [hidden] { display: none !important; }
    .icon { width: 22px; height: 22px; fill: none; stroke: currentColor; stroke-width: 1.7; stroke-linecap: round; stroke-linejoin: round; }
    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0;
    }

    /* Package media layout */
    .package-media {
      display: grid;
      grid-template-columns: minmax(0, 690fr) minmax(0, 337fr) minmax(0, 338fr) minmax(0, 428fr);
      gap: 15px;
      margin: 18px 0px 0 0px;
      height: 360px;
    }
    .media-tile {
      position: relative;
      display: block;
      width: 100%;
      min-width: 0;
      padding: 0;
      overflow: hidden;
      border: 0;
      border-radius: 6px;
      color: #fff;
      text-align: left;
      isolation: isolate;
    }
    .photo {
      position: absolute;
      inset: 0;
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
      pointer-events: none;
      transition: filter .2s ease;
    }
    .media-tile:hover > .photo { filter: brightness(1.055); }
    .media-tile:active > .photo { filter: brightness(.94); }
    .gallery-action {
      position: absolute;
      left: 3%;
      bottom: 3%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      width: 34.5%;
      min-width: 228px;
      height: 10.3%;
      min-height: 44px;
      padding: 0 12px;
      border: 1px solid #a7a7a7;
      border-radius: 40px;
      background: rgb(30 162 209);
      font-size: clamp(12px, 1.057vw, 20px);
      font-weight: 700;
      white-space: nowrap;
      transition: background .2s, border-color .2s;
    }
    .gallery-action .icon { width: 27px; height: 27px; }
    .gallery-action .arrow { font-size: 25px; font-weight: 400; line-height: 1; }
    .hero-photo:hover .gallery-action { border-color: #fff; }

    .highlight-card {
      display: flex;
      flex-direction: column;
      min-width: 0;
      overflow: hidden;
      padding: 25px clamp(14px, 1.058vw, 20px) 24px clamp(14px, 1.163vw, 22px);
      border: 1px solid #ffd191;
      border-radius: 6px;
      background: var(--cream);
    }
    .highlight-badge {
      align-self: anchor-center;
      flex-shrink: 0;
      /*margin: 0;*/
      margin-top: -17px;
      padding: 0 13px;
      border-radius: 30px;
      background: linear-gradient(105deg, #1fa4d2, #1fa4d2);
      color: #fff;
      font-size: clamp(12px, .846vw, 16px);
      font-weight: 700;
      line-height: 24px;
      white-space: nowrap;
    }
    .package-highlight-list {
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      gap: 10px;
      margin: 10px 0 0;
      padding: 0;
      list-style: none;
    }
    .package-highlight-list li { display: flex; align-items: flex-start; gap: 8px; }
    .package-highlight-list .check-icon {
      width: 20px;
      height: 20px;
      margin-top: 2px;
      color: #555;
      stroke-width: 1;
    }
    .highlight-name {
      display: block;
      font-size: clamp(15px, .952vw, 18px);
      font-weight: 500;
      line-height: 22px;
    }
    .highlight-meta {
      display: block;
      color: #545454;
      font-size: clamp(14px, .846vw, 16px);
      line-height: 22px;
    }
    .view-highlights {
      align-self: anchor-center;
      flex-shrink: 0;
      margin: 5px 0 0;
      padding: 0;
      border: 0;
      background: transparent;
      color: var(--blue);
      font-size: clamp(14px, .952vw, 18px);
      font-weight: 700;
      line-height: 22px;
      text-align: right;
    }
    .view-highlights:hover { color: #0076cd; text-decoration: underline; text-underline-offset: 4px; }
    .highlight-photo { flex: 1; min-height: 150px; margin-top: 6px; }
    .explore-stack {
      display: grid;
      grid-template-rows: minmax(0, 217fr) minmax(0, 219fr);
      gap: 15px;
      min-width: 0;
      min-height: 0;
    }
    .sunset-tile { 
      /* Removed background: #000; */
    }
    .image-hover-control {
      position: absolute;
      top: 50%;
      left: 50%;
      display: grid;
      place-items: center;
      width: 47px;
      height: 47px;
      border: 1px solid #ffffffb0;
      border-radius: 50%;
      background: #0008;
      opacity: 0;
      transform: translate(-50%, -45%);
      transition: opacity .2s, transform .2s;
    }
    .media-tile:hover .image-hover-control,
    .media-tile:focus-visible .image-hover-control {
      /*opacity: 1;*/
      transform: translate(-50%, -50%);
    }
    .tile-caption {
      position: absolute;
      right: 0;
      bottom: 0;
      left: 0;
      display: flex;
      align-items: flex-end;
      height: max(70px, 16%);
      padding: 0 15px 17px;
      background: linear-gradient(transparent, #000e8);
      font-size: clamp(13px, 1.111vw, 21px);
      font-weight: 700;
      line-height: 26px;
      letter-spacing: -.4px;
    }
    .property-tile .tile-caption {
      background: linear-gradient(to bottom, transparent 0%, #000 43%, #000 100%);
    }

    /* Accessible gallery and highlight dialogs */
    body.modal-open { overflow: hidden; }
    dialog {
      width: min(1120px, calc(100vw - 48px));
      max-width: none;
      max-height: calc(100dvh - 48px);
      margin: auto;
      padding: 0;
      overflow: hidden;
      border: 0;
      border-radius: 16px;
      background: #fff;
      color: var(--ink);
      box-shadow: 0 24px 100px #0005;
    }
    dialog[open] {
      display: flex;
      flex-direction: column;
      animation: modal-enter .2s ease-out;
    }
    dialog::backdrop { background: rgba(12, 20, 27, .76); backdrop-filter: blur(5px); }
    @keyframes modal-enter {
      from { opacity: 0; transform: translateY(10px) scale(.985); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-header {
      display: flex;
      flex-shrink: 0;
      justify-content: space-between;
      align-items: flex-start;
      gap: 20px;
      padding: 25px 28px 20px;
    }
    .modal-eyebrow {
      display: block;
      margin-bottom: 7px;
      color: #da654a;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1.6px;
      text-transform: uppercase;
    }
    .modal-title { margin: 0; font-size: 27px; font-weight: 600; line-height: 1.2; letter-spacing: -.7px; }
    .modal-subtitle { margin: 8px 0 0; color: var(--muted); font-size: 14px; line-height: 1.5; }
    .icon-button {
      display: grid;
      flex-shrink: 0;
      place-items: center;
      width: 40px;
      height: 40px;
      padding: 0;
      border: 1px solid var(--border);
      border-radius: 50%;
      background: #fff;
      color: #333;
      transition: background .15s, transform .15s;
    }
    .icon-button:hover { background: #f2f4f6; }
    .icon-button:active { transform: scale(.95); }
    .modal-tabs {
      display: flex;
      flex-shrink: 0;
      gap: 25px;
      padding: 0 28px;
      overflow-x: auto;
      border-bottom: 1px solid var(--border);
      scrollbar-width: none;
    }
    .modal-tabs::-webkit-scrollbar { display: none; }
    .modal-tabs [role="tab"] {
      position: relative;
      flex-shrink: 0;
      padding: 14px 0 15px;
      border: 0;
      background: none;
      color: #73767a;
      font-size: 14px;
      font-weight: 500;
      white-space: nowrap;
    }
    .modal-tabs [role="tab"]::after {
      position: absolute;
      right: 0;
      bottom: 0;
      left: 0;
      height: 3px;
      border-radius: 4px 4px 0 0;
      background: var(--blue);
      content: "";
      transform: scaleX(0);
      transition: transform .18s;
    }
    .modal-tabs [role="tab"][aria-selected="true"] { color: #008adf; }
    .modal-tabs [role="tab"][aria-selected="true"]::after { transform: scaleX(1); }
    .tab-count {
      display: inline-block;
      margin-left: 5px;
      padding: 2px 6px;
      border-radius: 5px;
      background: #f0f2f4;
      font-size: 11px;
    }
    .gallery-content {
      display: grid;
      grid-template-columns: minmax(0, 1fr) 270px;
      min-height: 0;
      overflow: auto;
      overscroll-behavior: contain;
    }
    .gallery-viewer { min-width: 0; background: #f5f6f7; }
    .gallery-stage {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      height: min(54vh, 540px);
      min-height: 260px;
      overflow: hidden;
      background: #111716;
    }
    .gallery-picture { position: relative; width: 100%; height: 100%; overflow: hidden; }
    .gallery-stage .gallery-arrow {
      position: absolute;
      top: 50%;
      width: 42px;
      height: 42px;
      border-color: #ffffff50;
      background: #1119;
      color: #fff;
      transform: translateY(-50%);
      backdrop-filter: blur(6px);
    }
    .gallery-stage .gallery-arrow:hover { background: #111e; }
    .gallery-previous { left: 14px; }
    .gallery-next { right: 14px; }
    .gallery-counter {
      position: absolute;
      right: 16px;
      bottom: 15px;
      padding: 6px 11px;
      border: 1px solid #ffffff30;
      border-radius: 20px;
      background: #111a;
      color: #fff;
      font-size: 12px;
      font-variant-numeric: tabular-nums;
    }
    .gallery-thumbnails {
      display: flex;
      gap: 9px;
      padding: 14px;
      overflow-x: auto;
      scrollbar-width: thin;
    }
    .thumbnail {
      position: relative;
      flex: 0 0 87px;
      height: 61px;
      padding: 0;
      overflow: hidden;
      border: 3px solid transparent;
      border-radius: 7px;
      background: #d8dfe1;
      opacity: .68;
      transition: opacity .15s, border-color .15s;
    }
    .thumbnail:hover { opacity: 1; }
    .thumbnail[aria-current="true"] { border-color: var(--blue); opacity: 1; }
    .gallery-info { display: flex; flex-direction: column; align-items: flex-start; padding: 27px 23px; }
    .location-label { display: flex; align-items: center; gap: 6px; margin-bottom: 15px; color: #6b7276; font-size: 12px; }
    .location-label .icon { width: 15px; height: 15px; }
    .gallery-photo-title { margin: 0; font-size: 23px; font-weight: 600; letter-spacing: -.5px; line-height: 1.25; }
    .gallery-photo-description { margin: 14px 0 20px; color: #62686c; font-size: 14px; line-height: 1.75; }
    .day-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 10px;
      border-radius: 6px;
      background: #fff2df;
      color: #a7642d;
      font-size: 12px;
      font-weight: 500;
    }
    .day-tag .icon { width: 15px; height: 15px; }
    .text-button {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      padding: 10px 0;
      border: 0;
      background: transparent;
      color: #008cde;
      font-size: 13px;
      font-weight: 600;
      text-align: left;
    }
    .text-button:hover { color: #006cae; text-decoration: underline; text-underline-offset: 3px; }
    .text-button .icon { width: 17px; height: 17px; }
    .gallery-info > .text-button { margin-top: auto; padding-top: 25px; }
    .gallery-keyboard-hint { margin: 13px 0 0; color: #93999d; font-size: 11px; line-height: 1.6; }

    .highlights-dialog { width: min(820px, calc(100vw - 48px)); }
    .highlights-dialog .modal-header { background: linear-gradient(115deg, #fff1db, #fff9f1); }
    .highlights-dialog .highlight-badge { display: inline-block; margin-bottom: 12px; font-size: 11px; line-height: 22px; }
    .highlights-dialog .modal-tabs { gap: 30px; }
    .highlights-scroll { min-height: 0; padding: 23px 28px; overflow-y: auto; overscroll-behavior: contain; }
    .experience-list { display: flex; flex-direction: column; gap: 12px; }
    .experience { overflow: hidden; border: 1px solid #e7e9eb; border-radius: 10px; }
    .experience-toggle {
      display: flex;
      align-items: center;
      gap: 15px;
      width: 100%;
      padding: 13px;
      border: 0;
      background: #fff;
      text-align: left;
    }
    .experience-toggle:hover { background: #fafbfc; }
    .experience-thumbnail { position: relative; flex-shrink: 0; width: 81px; height: 64px; overflow: hidden; border-radius: 6px; background: #e1e7e6; }
    .experience-heading { flex: 1; min-width: 0; }
    .experience-day { display: block; margin-bottom: 5px; color: #83888c; font-size: 12px; }
    .experience-title { display: block; font-size: 16px; font-weight: 600; line-height: 1.35; }
    .experience-toggle > .icon { width: 20px; height: 20px; margin: 0 4px; color: #777; transition: transform .2s; }
    .experience-toggle[aria-expanded="true"] > .icon { color: var(--blue); transform: rotate(180deg); }
    .experience-detail { padding: 0 19px 15px 109px; }
    .experience-detail p { margin: 0 0 13px; color: #686e73; font-size: 14px; line-height: 1.7; }
    .experience-tags { display: flex; flex-wrap: wrap; gap: 6px; margin: 0 0 5px; padding: 0; list-style: none; }
    .experience-tags li { padding: 5px 8px; border-radius: 5px; background: #f2f6f6; color: #58736c; font-size: 11px; }
    .itinerary-intro { margin: 0 0 20px; color: #747a7e; font-size: 13px; line-height: 1.7; }
    .day-card { position: relative; margin-bottom: 12px; border: 1px solid var(--border); border-radius: 9px; }
    .day-card summary {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 15px;
      cursor: pointer;
      list-style: none;
    }
    .day-card summary::-webkit-details-marker { display: none; }
    .day-number { display: grid; flex-shrink: 0; place-items: center; width: 38px; height: 38px; border-radius: 50%; background: #fff0db; color: #b16d39; font-size: 14px; font-weight: 600; }
    .day-summary { flex: 1; }
    .day-summary strong { display: block; font-size: 14px; font-weight: 500; }
    .day-summary small { display: block; margin-top: 5px; color: #81878b; font-size: 12px; }
    .day-card summary > .icon { width: 18px; height: 18px; color: #92979a; transition: transform .2s; }
    .day-card[open] summary > .icon { transform: rotate(180deg); }
    .day-description { margin: 0; padding: 0 24px 18px 68px; color: #687075; font-size: 14px; line-height: 1.75; }
    .highlights-footer {
      display: flex;
      flex-shrink: 0;
      align-items: center;
      justify-content: space-between;
      gap: 15px;
      padding: 17px 28px;
      border-top: 1px solid var(--border);
      background: #fafbfc;
    }
    .highlights-footer p { margin: 0; color: #868b90; font-size: 12px; line-height: 1.5; }
    .primary-button {
      flex-shrink: 0;
      min-height: 42px;
      padding: 11px 17px;
      border: 0;
      border-radius: 7px;
      background: var(--blue);
      color: #fff;
      font-size: 13px;
      font-weight: 600;
      transition: background .15s;
    }
    .primary-button:hover { background: #007ddc; }

    @media (min-width: 2200px) {
      .highlight-card { padding-top: 29px; }
      .package-highlight-list { gap: 15px; margin-top: 16px; }
      .highlight-photo { margin-top: 40px; }
    }
    @media (max-width: 1100px) {
      .package-media {
        grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
        grid-auto-rows: 400px;
        height: auto;
        margin: 18px 20px;
      }
      .highlight-card { padding: 25px 22px 24px; }
      .highlight-badge { font-size: 15px; }
      .highlight-name { font-size: 18px; }
      .highlight-meta { font-size: 16px; }
      .view-highlights { font-size: 17px; }
      .tile-caption { font-size: 21px; }
      .gallery-action { font-size: 18px; }
      .gallery-content { grid-template-columns: minmax(0, 1fr) 235px; }
      .gallery-info { padding: 24px 20px; }
    }
    @media (max-width: 700px) {
      dialog, .highlights-dialog { width: calc(100vw - 24px); max-height: calc(100dvh - 24px); border-radius: 12px; }
      .modal-header { padding: 21px 20px 17px; gap: 12px; }
      .modal-title { font-size: 23px; }
      .modal-subtitle { font-size: 13px; }
      .modal-tabs { gap: 20px; padding: 0 20px; }
      .modal-tabs [role="tab"] { font-size: 12px; }
      .gallery-content { display: block; }
      .gallery-stage { height: 40dvh; min-height: 230px; }
      .gallery-info { display: block; padding: 20px; }
      .gallery-photo-title { font-size: 22px; }
      .gallery-photo-description { margin: 10px 0 12px; }
      .gallery-info > .text-button { display: flex; padding-top: 17px; }
      .gallery-keyboard-hint { display: none; }
      .location-label { margin-bottom: 9px; }
      .highlights-scroll { padding: 18px 16px; }
      .experience-toggle { gap: 11px; padding: 11px; }
      .experience-thumbnail { width: 67px; height: 59px; }
      .experience-title { font-size: 14px; }
      .experience-detail { padding: 0 15px 13px 89px; }
      .experience-detail p { font-size: 13px; }
      .highlights-footer { padding: 15px 20px; }
      .highlights-footer p { max-width: 170px; font-size: 11px; }
    }
    @media (max-width: 640px) {
      .package-media { grid-template-columns: 1fr; grid-auto-rows: auto; gap: 14px; margin: 14px; }
      .hero-photo { aspect-ratio: 690 / 451; min-height: 250px; }
      .highlight-card { height: 430px; padding: 24px 22px; }
      .highlight-badge { font-size: 16px; }
      .highlight-photo { margin-top: 26px; }
      .explore-stack { height: 200px; grid-template-columns: 1fr 1fr; grid-template-rows: 1fr; gap: 14px; }
      .property-tile { height: 340px; }
      .tile-caption { padding-left: 12px; padding-bottom: 14px; font-size: 18px; line-height: 23px; }
      .gallery-action { left: 3%; bottom: 3%; }
    }
    @media (max-width: 420px) {
      .explore-stack { height: auto; grid-template-columns: 1fr; grid-template-rows: 210px 220px; }
      .tile-caption { font-size: 20px; padding: 0 15px 17px; }
      .experience-detail { padding-left: 15px; padding-top: 3px; }
      .highlights-footer p { max-width: 125px; }
      .primary-button { padding-right: 12px; padding-left: 12px; font-size: 12px; }
      .modal-tabs { gap: 15px; padding: 0 16px; }
      .tab-count { margin-left: 2px; }
    }
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after { animation: none !important; transition: none !important; scroll-behavior: auto !important; }
    }
</style>
@endsection
@section('content')
    <!-- Breadcrumb -->
<div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">{{ $package_details->package_title }}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item"><a href=""><i class="isax isax-home5"></i></a></li>
                            <li class="breadcrumb-item">Tours</li>
                            <li class="breadcrumb-item active" aria-current="page">Tour Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadcrumb -->
<div class="content">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="mb-2">
                                    <h4 class="mb-1 d-flex align-items-center flex-wrap">{{ $package_details->package_title }}</h4>
                                </div>
                            </div>
                 
                 @if(!empty($package_highlights_property))
                 <main class="mb-3">
                    <section class="package-media" aria-label="Krabi and Phuket package photos and highlights">
                        <!-- 1) Activities & Sightseeing - Top Left -->
                        <button class="media-tile property-tile" type="button" data-gallery="property" data-photo="resort" aria-label="View property photos">
                            <img class="photo" src="{{asset('uploads/highlights/'.$package_highlights_property->image)}}" alt="Property resort view" loading="lazy">
                            <span class="tile-caption" data-bs-toggle="modal" data-bs-target="#property_photos">Property photos</span>
                        </button>
                        <div class="explore-stack">
                            <button class="media-tile sunset-tile" type="button" data-gallery="activities" data-photo="sunset" aria-label="View sunset at Promthep Cape">
                                <iframe width="100%" height="250" src="{{$highlights_images->youtube_link}}" frameborder="0"allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                <span class="image-hover-control" aria-hidden="true"><svg class="icon"><use href="#icon-expand"/></svg></span>
                            </button>
                            <button class="media-tile activity-tile" type="button" data-gallery="activities" data-photo="railay" aria-label="View activities and sightseeing photos">
                                <img class="photo" src="{{asset('uploads/highlights/'.$package_highlights_sightseeing->image)}}" alt="Activities and sightseeing" loading="lazy">
                                <span class="tile-caption" data-bs-toggle="modal" data-bs-target="#activities_and_sightseeing">Activities &amp; Sightseeing</span>
                            </button>
                        </div>
                
                        <!-- 2) Property photos - Top Center -->
                        
                
                        <!-- 3) PACKAGE HIGHLIGHTS - Top Right -->
                        <aside class="highlight-card" aria-labelledby="package-highlights-heading">
                            <h2 class="highlight-badge" id="package-highlights-heading">PACKAGE HIGHLIGHTS</h2>
                            <ul class="package-highlight-list">
                                <li>{{$highlights_images->short_description}}</li>
                            </ul>
                            <button class="view-highlights" type="button" data-open-highlights>VIEW ALL HIGHLIGHTS</button>
                            <button class="media-tile highlight-photo" type="button" data-gallery="activities" data-photo="railay" aria-label="Explore the 7 Islands Sunset Tour">
                                <img class="photo" src="{{ asset('uploads/highlights/' . $highlights_images->highlights_image) }}" alt="Railay Beach view" loading="lazy">
                                <span class="image-hover-control" aria-hidden="true"><svg class="icon"><use href="#icon-expand"/></svg></span>
                            </button>
                        </aside>
                
                        <!-- 4) VIEW GALLERY - Bottom (Full Width) -->
                        <button class="media-tile hero-photo" type="button" data-gallery="all" data-photo="krabi" aria-label="View the Krabi and Phuket photo gallery">
                            <img class="photo" src="{{asset('uploads/highlights/'.$package_highlights_gallery->image)}}" alt="Krabi beach view" loading="lazy">
                            <span class="gallery-action" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <svg class="icon" aria-hidden="true"><use href="#icon-gallery"/></svg>
                                <span>VIEW GALLERY</span>
                                <span class="arrow" aria-hidden="true">→</span>
                            </span>
                        </button>
                    </section>
                </main>
                 @endif
            <div class="row">
                   
                <div class="col-xl-8">
                     <div>
                        <div class="service-wrap slider-wrap-four mb-4">
                            
                            <div class="service-wrap">
                                <div class="slider-wrap custom-mb">
                                    <div class="owl-carousel service-carousel nav-center mb-4" id="large-img">
                                         @foreach($package_images as $key => $img)
                                        <div class="service-img">
                                            <img src="{{ asset('uploads/package/' . $img->images) }}" class="img-fluid"
                                                alt="Slider Img" style="height:300px">
                                        </div>
                                         @endforeach
                                    </div>
                                </div>
                                <div class="owl-carousel slider-nav-thumbnails nav-center" id="small-img">
                                     @foreach($package_images as $key => $img)
                                    <div><img src="{{ asset('uploads/package/' . $img->images) }}" class="img-fluid"
                                            alt="Slider Img" style="height: 100px;"></div>
                                     @endforeach
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <div class="sub-menu-new">
                              <ul>
                                  <li><a class="scroll" href="#over-01">OVERVIEW </a></li>
                                  <li><a class="scroll" href="#Itinerary">ITINERARY </a></li>
                                  <li><a class="scroll" href="#Inclusions">INCLUSIONS</a></li>
                              </ul>                                
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="mb-2">
                                <h4 class="mb-1 d-flex align-items-center flex-wrap mb-2" style="font-size: 24px;">{{$package_details->package_title}}</h4>
                                 
                                <div class="d-flex align-items-center flex-wrap">
                                    <p class="fs-14 mb-2 me-3 pe-3 border-end"><i
                                            class="isax isax-receipt text-primary me-2"></i>{{$package_details->destination_type}}</p>
                                    <p class="fs-14 mb-2 me-3 pe-3 border-end"><i
                                            class="isax isax-location5 me-2"></i>{{$package_details->pack_location_cover}}
                                    </p>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <span class="me-1"><i class="isax isax-calendar-tick text-gray-6"></i></span>
                                            <p class="fs-14 text-gray-9">{{$package_details->nights_count}} Night - {{$package_details->days_count}} Day</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Slider -->
                    <!--<div>-->
                    <!--    <div class="service-wrap mb-4">-->
                    <!--        <div class="slider-wrap vertical-slider tour-vertical-slide d-flex align-items-center">-->
                    <!--            <div class="slider-for nav-center" id="large-img">-->
                    <!--                @foreach($package_images as $key => $img)-->
                    <!--                <div class="service-img">-->
                    <!--                    <img src="{{ asset('uploads/package/' . $img->images) }}" class="img-fluid"-->
                    <!--                        alt="Slider Img" style="width:100%;height: 430px;">-->
                    <!--                </div>-->
                    <!--                @endforeach-->
                    <!--            </div>-->
                    <!--            <div class="slider-nav nav-center" id="small-img">-->
                    <!--                @foreach($package_images as $key => $img)-->
                    <!--                <div><img src="{{ asset('uploads/package/' . $img->images) }}" class="img-fluid"-->
                    <!--                        alt="Slider Img" style="width:75px;height: 75px;"></div>-->
                    <!--                @endforeach-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--    <div class="row">-->
                    <!--        <div class="col-md-12">-->
                    <!--            <div class="sub-menu-new">-->
                    <!--          <ul>-->
                    <!--              <li><a class="scroll" href="#over-01">OVERVIEW </a></li>-->
                    <!--              <li><a class="scroll" href="#Itinerary">ITINERARY </a></li>-->
                    <!--              <li><a class="scroll" href="#Inclusions">INCLUSIONS</a></li>-->
                    <!--          </ul>                                -->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--    <div class="d-flex align-items-center justify-content-between mb-2">-->
                    <!--        <div class="mb-2">-->
                    <!--            <h4 class="mb-1 d-flex align-items-center flex-wrap mb-2" style="font-size: 24px;">{{$package_details->package_title}}</h4>-->
                                 
                    <!--            <div class="d-flex align-items-center flex-wrap">-->
                    <!--                <p class="fs-14 mb-2 me-3 pe-3 border-end"><i-->
                    <!--                        class="isax isax-receipt text-primary me-2"></i>{{$package_details->destination_type}}</p>-->
                    <!--                <p class="fs-14 mb-2 me-3 pe-3 border-end"><i-->
                    <!--                        class="isax isax-location5 me-2"></i>{{$package_details->pack_location_cover}}-->
                    <!--                </p>-->
                    <!--                <div class="d-flex align-items-center mb-2">-->
                    <!--                    <div class="d-flex align-items-center flex-wrap">-->
                    <!--                        <span class="me-1"><i class="isax isax-calendar-tick text-gray-6"></i></span>-->
                    <!--                        <p class="fs-14 text-gray-9">{{$package_details->nights_count}} Night - {{$package_details->days_count}} Day</p>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!-- /Slider -->

                    <!-- Description -->
                    <div class="bg-light-200 card-bg-light mb-2" id="over-01">
                        <h5 class="fs-18 mb-3">Overview</h5>
                        <div class="mb-2">
                            <p style="font-size: 15px;">{{$package_details->overview}}
                            </p>
                        </div>
                    </div>
                    <!-- /Description -->

                    <!-- Highlights -->
                    <div class="bg-light-200 card-bg-light mb-4">
                        <!--<h5 class="fs-14 mb-3">Notes</h5>-->
                        <!--<div class="fs-13">-->
                        <!--    <div class="d-flex align-items-center mb-2">-->
                        <!--        <span class="avatar avatar-md bg-primary-transparent rounded-circle me-2">-->
                        <!--            <i class="isax isax-send-sqaure-2 fs-16"></i>-->
                        <!--        </span>-->
                        <!--        <p>Buffet breakfast as per the Itinerary</p>-->
                        <!--    </div>-->
                        <!--    <div class="d-flex align-items-center mb-2">-->
                        <!--        <span class="avatar avatar-md bg-primary-transparent rounded-circle me-2">-->
                        <!--            <i class="isax isax-send-sqaure-2 fs-16"></i>-->
                        <!--        </span>-->
                        <!--        <p>Visit eight villages showcasing Polynesian culture</p>-->
                        <!--    </div>-->
                        <!--    <div class="d-flex align-items-center mb-2">-->
                        <!--        <span class="avatar avatar-md bg-primary-transparent rounded-circle me-2">-->
                        <!--            <i class="isax isax-send-sqaure-2 fs-16"></i>-->
                        <!--        </span>-->
                        <!--        <p>Complimentary Camel safari, Bonfire,</p>-->
                        <!--    </div>-->
                        <!--    <div class="d-flex align-items-center mb-2">-->
                        <!--        <span class="avatar avatar-md bg-primary-transparent rounded-circle me-2">-->
                        <!--            <i class="isax isax-send-sqaure-2 fs-16"></i>-->
                        <!--        </span>-->
                        <!--        <p>All toll tax, parking, fuel, and driver allowances</p>-->
                        <!--    </div>-->
                        <!--    <div class="d-flex align-items-center mb-2">-->
                        <!--        <span class="avatar avatar-md bg-primary-transparent rounded-circle me-2">-->
                        <!--            <i class="isax isax-send-sqaure-2 fs-16"></i>-->
                        <!--        </span>-->
                        <!--        <p>Comfortable and hygienic vehicle</p>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                    <!-- /Highlights -->
                    @if(!empty($vehicle_images) && count($vehicle_images) > 0)
                    <!-- Gallery -->
                    <div class="bg-light-200 card-bg-light mb-4">
                        <h5 class="fs-18 mb-3">Vehicle</h5>
                        <div class="tour-gallery-slider owl-carousel">
                            @foreach($vehicle_images as $key => $val)
                            <a class="galley-wrap" data-fancybox="gallery"
                                href="{{ asset('uploads/package/' . $val->images) }}">
                                <img src="{{ asset('uploads/package/' . $val->images) }}" alt="img">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    <!-- /Gallery -->
                    @endif
                    <!-- Gallery -->
                    @if(!empty($hotels_images) && count($hotels_images) > 0)
                    <div class="bg-light-200 card-bg-light mb-4">
                        <h5 class="fs-18 mb-3">Hotels</h5>
                        <div class="tour-gallery-slider owl-carousel">
                            @foreach($hotels_images as $key => $val)
                            <a class="galley-wrap" data-fancybox="gallery"
                                href="{{ asset('uploads/package/' . $val->images) }}">
                                <img src="{{ asset('uploads/package/' . $val->images) }}" alt="img">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <!-- /Gallery -->
                    <div class="bg-light-200 card-bg-light mb-4" id="Itinerary">
                        <h5 class="fs-18 mb-3">Itinerary</h5>
                        
                        <!-- Static Transport Design -->
                        
                        <!-- End Static Transport Design -->
                    
                        <div class="accordion faq-accordion" id="itineraryAccordion">
                            
                        <div class="transport-card mb-4" style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div class="d-flex align-items-center">
                                    <div class="transport-icon me-3" style="background: #1fb1d7; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-plane-departure" style="color: white; font-size: 24px;"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center flex-wrap">
                                            <span class="badge me-2" style="background: #4c82ff24; color: #1fb1d7; padding: 5px 12px; border-radius: 20px; font-weight: bold;">0 days</span>
                                            <span style="font-weight: 600; font-size: 16px;">{{$package_flight?->start_place}}</span>
                                            <i class="isax isax-arrow-right mx-2" style="color: #6c757d;"></i>
                                            <span style="font-weight: 600; font-size: 16px;">{{$package_flight?->end_place}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="transport-time d-flex align-items-center mt-2 mt-sm-0">
                                    <div class="me-3 text-center">
                                        <span style="font-size: 14px; color: #6c757d;">Departure</span>
                                        <span style="display: block; font-weight: 600;">{{$package_flight?->from_time}}</span>
                                        <span style="font-size: 12px; color: #6c757d;">{{ \Carbon\Carbon::parse($package_flight?->form_date)->format('D, d M') }}</span>
                                    </div>
                                    <i class="isax isax-arrow-right mx-2" style="color: #1fb1d7;"></i>
                                    <div class="text-center">
                                        <span style="font-size: 14px; color: #6c757d;">Arrival</span>
                                        <span style="display: block; font-weight: 600;">{{$package_flight?->to_time}}</span>
                                        <span style="font-size: 12px; color: #6c757d;">{{ \Carbon\Carbon::parse($package_flight?->to_date)->format('D, d M') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="transport-details mt-2 pt-2" style="border-top: 1px dashed #dee2e6;">
                                <div class="d-flex align-items-center flex-wrap">
                                    <span class="me-3" style="font-size: 13px; color: #6c757d;"><i class="isax isax-ticket me-1"></i>Cabin: {{$package_flight?->cabin_place}}</span>
                                    <span style="font-size: 13px; color: #6c757d;"><i class="isax isax-clock me-1"></i>Check-in: {{$package_flight?->check_in}}</span>
                                </div>
                            </div>
                        </div>
                            @foreach($package_itinerary as $key => $val)
                            <div class="accordion-item mb-3">
                                <div class="accordion-header">
                                    <button class="accordion-button fw-medium {{ $key !== 0 ? 'collapsed' : '' }}" 
                                        type="button" 
                                        data-bs-toggle="collapse"
                                        data-bs-target="#faq-collapse_{{ $key + 1 }}" 
                                        aria-expanded="{{ $key === 0 ? 'true' : 'false' }}"
                                        aria-controls="faq-collapse_{{ $key + 1 }}">
                                        <span class="me-2-day">Day {{ $key + 1 }}</span> {{ $val->question }}
                                    </button>
                                </div>
                                <div id="faq-collapse_{{ $key + 1 }}" 
                                    class="accordion-collapse collapse {{ $key === 0 ? 'show' : '' }}"
                                    data-bs-parent="#itineraryAccordion">
                                    <div class="accordion-body">
                                        <p class="mb-3">{{ $val->answer }}</p>
                                        
                                        @if(!empty($val->image) || !empty($val->image_two))
                                        <div class="row mt-3">
                                            @if(!empty($val->image))
                                            <div class="col-md-6 mb-2 mb-md-0">
                                                <img src="{{ asset($val->image) }}" 
                                                     alt="Itinerary Image" 
                                                     style="height: 180px; width: 100%; border-radius: 13px; object-fit: cover;">
                                            </div>
                                            @endif
                                            @if(!empty($val->image_two))
                                            <div class="col-md-6">
                                                <img src="{{ asset($val->image_two) }}" 
                                                     alt="Itinerary Image Two" 
                                                     style="height: 180px; width: 100%; border-radius: 13px; object-fit: cover;">
                                            </div>
                                            @endif
                                            
                                            @if(!empty($itinerary_image) && count($itinerary_image) > 0)
                                            <div class="bg-light-200 card-bg-light mb-4">
                                                <div class="tour-gallery-slider owl-carousel">
                                                    @foreach($itinerary_image as $key => $row)
                                                    @if($val->id == $row->itinerary_id)
                                                    <a class="galley-wrap" data-fancybox="gallery"
                                                        href="{{ asset('uploads/itinerary/' . $row->image) }}">
                                                        <img src="{{ asset('uploads/itinerary/' . $row->image) }}" alt="img">
                                                    </a>
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                            
                                            <div class="d-flex align-items-center justify-content-between border-top pt-3">
                                                <div class="w-100">
                                                    @php
                                                        $amenities = explode('##', $val->short_description);
                                                    @endphp
                                            
                                                    <div class="row ms-2 fs-12">
                                                        @foreach($amenities as $item)
                                                            @if(trim($item) !== '')
                                                                <div class="col-md-4 col-4 mb-2">
                                                                    <li>{{ trim($item) }}</li>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                          
                                        
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Itinerary -->
                    
                    

                    <!-- Includes & Excludes -->
                    <div class="bg-light-200 card-bg-light mb-4" id="Inclusions">
                        <h5 class="fs-16 mb-3">Includes & Excludes</h5>
                        <div class="row gy-2 fs-13">
                            <div class="col-md-6">
                                @php 
                                $inclusion = explode('##', $package_details->inclusions); 
                                $exclusion = explode('##', $package_details->exclusions);
                                @endphp
                                @foreach($inclusion as $item)
                                        @if(trim($item) !== '')
                                            <p class="d-flex align-items-center mb-2">
                                                <i class="isax isax-tick-square5 text-success me-2"></i> {{ trim($item) }}
                                            </p>
                                        @endif
                                @endforeach
                                
                            </div>
                            <div class="col-md-6">
                                @foreach($exclusion as $item)
                                  @if(trim($item) !== '')
                                    <p class="d-flex align-items-center mb-2">
                                        <i class="isax isax-close-square5 text-danger me-2"></i> {{ trim($item) }}
                                    </p>
                                  @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- /Includes & Excludes -->

                    <!-- Gallery -->
                    <div class="bg-light-200 card-bg-light mb-6">
                        <h5 class="fs-18 mb-3">Gallery</h5>
                        <div class="tour-gallery-slider owl-carousel">
                            @foreach($package_images as $key => $val)
                            <a class="galley-wrap" data-fancybox="gallery"
                                href="{{ asset('uploads/package/' . $val->images) }}">
                                <img src="{{ asset('uploads/package/' . $val->images) }}" alt="img">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    <!-- /Gallery -->
                </div>
                <!-- /Reviews -->

                <!-- Tour Sidebar -->
                <div class="col-xl-4 theiaStickySidebar">
                    <div class="card bg-light-200">
                        <div class="card-body">
                            <div class="tour-detail-header">
                                <span class="avatar avatar-md rounded-circle bg-primary me-2">
                                    <i class="isax isax-signpost5"></i>
                                </span>
                                <h5 class="fs-14 mb-0 fw-semibold">Tour Details</h5>
                            </div>
                            <div>
                                <div class="d-flex align-items-center justify-content-between details-info">
                                    <h6 class="fw-medium">Tour Name</h6>
                                    <p class="fs-15 flex-fill text-end">{{ $package_details->package_title }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between details-info">
                                    <h6 class="fw-medium">Tour Code</h6>
                                    <p class="fs-15 flex-fill text-end">{{ $package_details->package_id }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between details-info">
                                    <h6 class="fw-medium">Duration</h6>
                                    <p class="fs-15 flex-fill text-end">{{$package_details->days_count}} Days, {{$package_details->nights_count}} Nights</p>
                                </div>
                                <div class="details-info pb-2">
                                    <h6 class="fw-medium mb-2">Places Covered</h6>
                                    <p class="fs-14 places-covered-text">{{ $package_details->pack_location_cover }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-none">
                        <div class="card-body">
                        <form id="news_comment_form" method="POST">
                            <div class="col-lg-12">
                                <div class="form-froup mt-2">
                                    <label class="form-label fs-14 text-default mb-0">Select Package Type</label>
                                    <select class="form-select bg_input" name="package_price_type" onchange="setvalue(this.value);">
                                        <option value="">Package Type</option>
                                        <!--<option value="Budget" {{ request()->get('type') == 'budget' ? 'selected' : '' }}>Budget</option>-->
                                         <option value="Standard" {{ request()->get('type') == 'standard' ? 'selected' : '' }}>Standard</option>
                                        <option value="Premium" {{ request()->get('type') == 'premium' ? 'selected' : '' }}>Premium</option>
                                        <option value="Luxury" {{ request()->get('type') == 'luxury' ? 'selected' : '' }}>Luxury</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="mb-3">
                                <h5 class="mt-2 fs-6">
                                    <span class="fs-13 fw-medium mb-1">Price : </span>
                                    <span style="font-size: 18px; color: #fd7871;">₹</span> 
                                    <b id="result" style="font-size: 18px; color: #dd5df4;">{{ request()->get('price') ? request()->get('price') : number_format(get_dynamic_tour_cal('Standard', 2, $package_details->tour_id,$package_details->days_count,$package_details->nights_count)) }}</b>
                                    <span class="fs-13 fw-medium mb-1" style="font-weight: bold;"> Per Person (Twin Sharing)</span>
                                </h5>
                            </div>
                            <div class="banner-form">
                                
                                    <input type="hidden" class="form-control bg_input" name="pageid" value="andhrapilgrimagetour">
                                    <input type="hidden" class="form-control bg_input" name="package_id" value="{{ $package_details->id }}">
                                    <input type="hidden" class="form-control bg_input" name="tour_name" value="{{ $package_details->package_title }}">
                                    <input type="hidden" class="form-control bg_input" id="nights_count" name="nights_count" value="{{ $package_details->nights_count }}">
                                    <input type="hidden" class="form-control bg_input"  name="days_count" value="{{ $package_details->days_count }}">
                                    <input type="hidden" placeholder="To" name="to_date" class="input form-control bg_input w-100" id="result_date_two" required readonly>
                                    <input type="hidden" placeholder="To" name="price" class="input form-control bg_input w-100" id="result_two" required readonly>
                                    <div class="form-info border-0">
                                        <div class="form-item border rounded p-3 mb-3 w-100">
                                            <label class="form-label fs-12 text-default mb-0">From</label>
                                            <input type="text" class="form-control fs-12" name="from_date" id="dateInput" required onchange="change_date(this.value);" style="font-size: 15px;">
                                        </div>
                                        <div class="form-item border rounded p-3 mb-3 w-100">
                                            <label class="form-label fs-12 text-default mb-0">To</label>
                                            <input type="text" class="form-control fs-12" id="result_date" value="<?=date('d-m-Y')?>" required readonly style="font-size: 15px;">
                                        </div>
                                        <div class="card shadow-none mb-3">
                                            <div class="card-body p-3 pb-0">
                                                <div class="border-bottom pb-2 mb-2">
                                                    <h6>Details</h6>
                                                </div>
                                                <div class="custom-increment">
                                                    <div class="mb-3 d-flex align-items-center justify-content-between">
                                                        <label class="form-label text-gray-9 mb-0">Adults</label>
                                                        <div class="custom-increment">
                                                            <div class="input-group">
                                                                <span class="input-group-btn float-start">
                                                                    <button type="button"
                                                                        class="quantity-left-minus btn btn-light btn-number"
                                                                        data-type="minus" data-field="">
                                                                        <span><i class="isax isax-minus"></i></span>
                                                                    </button>
                                                                </span>
                                                                <input type="text" name="adults" class=" input-number"
                                                                    value="0">
                                                                <span class="input-group-btn float-end">
                                                                    <button type="button"
                                                                        class="quantity-right-plus btn btn-light btn-number"
                                                                        data-type="plus" data-field="">
                                                                        <span><i class="isax isax-add"></i></span>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 d-flex align-items-center justify-content-between">
                                                        <label class="form-label text-gray-9 mb-0">Infants <span
                                                                class="text-default fw-normal">( 0-5 Yrs
                                                                )</span></label>
                                                        <div class="custom-increment">
                                                            <div class="input-group">
                                                                <span class="input-group-btn float-start">
                                                                    <button type="button"
                                                                        class="quantity-left-minus btn btn-light btn-number"
                                                                        data-type="minus" data-field="">
                                                                        <span><i class="isax isax-minus"></i></span>
                                                                    </button>
                                                                </span>
                                                                <input type="text" name="infants" class=" input-number"
                                                                    value="0">
                                                                <span class="input-group-btn float-end">
                                                                    <button type="button"
                                                                        class="quantity-right-plus btn btn-light btn-number"
                                                                        data-type="plus" data-field="">
                                                                        <span><i class="isax isax-add"></i></span>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 d-flex align-items-center justify-content-between">
                                                        <label class="form-label text-gray-9 mb-0">Children <span
                                                                class="text-default fw-normal">( 6-12 Yrs
                                                                )</span></label>
                                                        <div class="custom-increment">
                                                            <div class="input-group">
                                                                <span class="input-group-btn float-start">
                                                                    <button type="button"
                                                                        class="quantity-left-minus btn btn-light btn-number"
                                                                        data-type="minus" data-field="">
                                                                        <span><i class="isax isax-minus"></i></span>
                                                                    </button>
                                                                </span>
                                                                <input type="text" name="children" class=" input-number"
                                                                    value="0">
                                                                <span class="input-group-btn float-end">
                                                                    <button type="button"
                                                                        class="quantity-right-plus btn btn-light btn-number"
                                                                        data-type="plus" data-field="">
                                                                        <span><i class="isax isax-add"></i></span>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     @php
                                use App\Models\CustomerOtpModel;
                                use App\Models\CustomerModel;
                                $customer_id = \Session::get('customer_id');
                                $customer_details =  CustomerOtpModel::select('*')->where('customer_id', $customer_id)->orderBy('id', 'desc')->first();
                                $customer_detail =  CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
                                @endphp
                                @if(!empty($customer_id) && empty($customer_details->customer_id))
                                    <button type="submit" id="submit"
                                        class="btn btn-primary btn-lg search-btn ms-0 w-100 fs-14">Book Now</button>
                                 @else
                                <div><a href="#" class="btn btn-primary btn-lg search-btn ms-0 w-100 fs-14" data-bs-toggle="modal"
                                        data-bs-target="#login-modal">Sign In</a></div>
                                @endif
                                
                            </div>
                        </form>
                        </div>
                    </div>
                    <div class="card shadow-none">
                        <div class="card-body">
                            <h5 class="fs-14 mb-3">Why Book With Us</h5>
                            <div class="fs-13">
                                <p class="d-flex align-items-center mb-3"><i
                                        class="isax isax-medal-star text-primary me-2"></i>Expertise and Experience</p>
                                <p class="d-flex align-items-center mb-3"><i
                                        class="isax isax-menu text-primary me-2"></i>Tailored Services</p>
                                <p class="d-flex align-items-center mb-3"><i
                                        class="isax isax-message-minus text-primary me-2"></i>Comprehensive Planning</p>
                                <p class="d-flex align-items-center mb-3"><i
                                        class="isax isax-user-add text-primary me-2"></i>Client Satisfaction</p>
                                <p class="d-flex align-items-center"><i
                                        class="isax isax-grammerly text-primary me-2"></i>24/7 Support</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Tour Sidebar -->
                 <div class="col-xl-2"></div>
            </div>
        </div>
    </div>


<div class="modal fade" id="activities_and_sightseeing" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Activities & Sightseeing</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
        @foreach($package_highlights_sightseeing_all as $key => $val)
            <div class="col-6 col-md-4">
                <img src="{{ asset('uploads/highlights/' . $val->image) }}" alt="Gallery image" loading="lazy" style="border-radius: 10px;">
            </div>
        @endforeach
          
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="property_photos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Property Photos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
        @foreach($package_highlights_property_all as $key => $val)
            <div class="col-6 col-md-4">
                <img src="{{ asset('uploads/highlights/' . $val->image) }}" alt="Gallery image" loading="lazy" style="border-radius: 10px;">
            </div>
        @endforeach
          
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Gallery</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
        @foreach($package_highlights_gallery_all as $key => $val)
            <div class="col-6 col-md-4">
                <img src="{{ asset('uploads/highlights/' . $val->image) }}" alt="Gallery image" loading="lazy" style="border-radius: 10px;">
            </div>
        @endforeach
          
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
function getValue(val) {
    for(let i = 1; i <= 6; i++) {
        $(`#age${i}`).hide();
        $(`#child_age${i}`).prop('required', false);
    }
    for(let i = 1; i <= val; i++) {
        $(`#age${i}`).show();
        $(`#child_age${i}`).prop('required', true);
    }
}
</script>
<script>
$(function() {
  $('input[name="from_date"]').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    autoApply:true,
     locale: {
      format: 'DD-MM-YYYY'
    }
    
  });
   
});


function change_date(date){
    var package_id = {{ $package_details->id }};
    $.ajax({
        type: "POST",
        url: "{{ route('change.date') }}",
        data: {
            date: date,
            package_id: package_id,
            _token: "{{ csrf_token() }}"
        },
        success: function(data){
            $("#result_date").val(data);
            $("#result_date_two").val(data);
        }
    });
}


function setvalue(package_type){
    var package_id = {{ $package_details->id }};

    $.ajax({
        type: "POST",
        url: "{{ route('get.price') }}",
        data: {
            package_type: package_type,
            package_id: package_id,
            _token: "{{ csrf_token() }}"
        },
        cache: false,
        success: function(data){
            $("#result").html(data);
            $("#result_two").val(data);
        }
    });
}



</script>
    <script>
        $("#news_comment_form").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('pay.submit') }}", new FormData($("#news_comment_form")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Data added successfully', 'top right', 'success', true, "{{ route('submit.info') }}", 1000);
                else {
                    $("#submit").prop('disabled', false);
                    $("#submit").html('Submit');
                    for (var a in data['error']['message']) {
                        notify(null, data['error']['message'][a][0], 'botton left');
                        if (a == 'success' | a == 'error') notify(null, data['error']['message'][a][0],'botton left');
                    }
                }
            }).catch(error => {
                $("#submit").prop('disabled', false);
                $("#submit").html('Submit');
                notify(null, 'Something went wrong', 'top right');
                console.log(error);
            });
        });
    </script>
    

 @stop