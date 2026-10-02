<script>
  export let name = '';
  export let size = 20;
  export let cls = '';
  export let variant = 'default';

  const outlinePaths = {
    layers: '<path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 12 9 5 9-5M3 16l9 5 9-5"/>',
    wrench: '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94L14.7 6.3Z"/>',
    rocket: '<path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2Z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09Z"/><circle cx="16" cy="8" r="1.5"/>',
    briefcase: '<rect x="3" y="7" width="18" height="14" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12a24 24 0 0 0 18 0M12 11v3"/>',
    newspaper: '<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8M8 11h8M8 15h3M8 18h8"/><path d="M14 14h2v2h-2z"/>',
    'map-pin': '<path d="M20 10c0 6-8 11-8 11S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>',
    location: '<path d="M20 10c0 6-8 11-8 11S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>',
    envelope: '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
    instagram: '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".6"/>',
    whatsapp: '<path d="M20.5 11.8a8.5 8.5 0 0 1-12.7 7.4L3 20.5l1.3-4.7A8.5 8.5 0 1 1 20.5 11.8Z"/><path d="m8.7 7.5 1 2-.7.7c.5 1.2 1.4 2.1 2.6 2.6l.7-.7 2 1-.4.8c-.3.8-1.1 1-2.1.7-2.5-.7-4.5-2.7-5.2-5.2-.3-1-.1-1.8.7-2.1l1.4.2Z"/>',
    heart: '<path d="m12 20-8-8a5 5 0 0 1 7-7l1 1 1-1a5 5 0 0 1 7 7Z"/>',
  };

  const paths = {
    /* ── Service main ────────────────────────────────────────────── */
    layers: `
      <path d="M2 8L12 3L22 8L12 13L2 8Z" fill="currentColor" opacity="0.85"/>
      <path d="M2 13L12 18L22 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
      <path d="M2 18L12 23L22 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    wrench: `
      <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
      <circle cx="6.5" cy="17.5" r="1" fill="currentColor"/>`,
    rocket: `
      <path d="M12 15l-3-3a22 22 0 012-3.95A12.88 12.88 0 0122 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 01-4 2z" fill="currentColor" opacity="0.8"/>
      <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
      <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 00-2.91-.09z" fill="currentColor" opacity="0.65"/>`,
    /* ── Feature icons ───────────────────────────────────────────── */
    shield: `
      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" fill="currentColor" opacity="0.15" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
      <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    gauge: `
      <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10c0 2.42-.86 4.64-2.29 6.38" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
      <path d="M12 12l3.5-3.5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
      <circle cx="12" cy="12" r="2" fill="currentColor"/>`,
    headset: `
      <path d="M3 18v-6a9 9 0 0118 0v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
      <rect x="19" y="13" width="3" height="6" rx="1.5" fill="currentColor" opacity="0.8"/>
      <rect x="2" y="13" width="3" height="6" rx="1.5" fill="currentColor" opacity="0.8"/>
      <path d="M21 19v1a2 2 0 01-2 2h-1" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>`,
    bug: `
      <path d="M8 2l1.5 1.5M16 2l-1.5 1.5M9 9a3 3 0 016 0v4a3 3 0 01-6 0V9z" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="currentColor" fill-opacity="0.15"/>
      <path d="M3 11h3M18 11h3M4 17h3M17 17h3M6 7l2 2M18 7l-2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>`,
    server: `
      <rect x="2" y="2" width="20" height="8" rx="2" fill="currentColor" opacity="0.15" stroke="currentColor" stroke-width="2"/>
      <rect x="2" y="14" width="20" height="8" rx="2" fill="currentColor" opacity="0.15" stroke="currentColor" stroke-width="2"/>
      <circle cx="6" cy="6" r="1.5" fill="currentColor"/>
      <circle cx="6" cy="18" r="1.5" fill="currentColor"/>
      <path d="M10 6h4M10 18h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>`,
    'shield-virus': `
      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" fill="currentColor" opacity="0.12" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
      <circle cx="12" cy="12" r="2" fill="currentColor"/>
      <path d="M12 8.5v2M12 13.5v2M9 12H7M15 12h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/>`,
    merge: `
      <circle cx="18" cy="18" r="3" fill="currentColor" opacity="0.8"/>
      <circle cx="6" cy="6" r="3" fill="currentColor" opacity="0.8"/>
      <circle cx="18" cy="6" r="3" fill="currentColor" opacity="0.35"/>
      <path d="M6 9v3c0 3 3 4.5 6 4.5s6-1.5 6-4.5V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
      <line x1="6" y1="9" x2="6" y2="9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>`,
    performance: `
      <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" fill="currentColor" opacity="0.8" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>`,
    people: `
      <circle cx="18" cy="8" r="3" fill="currentColor" opacity="0.8"/>
      <circle cx="6" cy="8" r="3" fill="currentColor" opacity="0.8"/>
      <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
      <path d="M9 14l3-3 3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    gears: `
      <circle cx="12" cy="12" r="3" fill="currentColor" opacity="0.8"/>
      <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" stroke="currentColor" stroke-width="1.5" fill="none"/>`,
    chart: `
      <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    'shield-check': `
      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" fill="currentColor" opacity="0.12" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
      <polyline points="9 12 11 14 15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    eye: `
      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" fill="currentColor" opacity="0.1" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
      <circle cx="12" cy="12" r="3" fill="currentColor"/>`,
    scale: `
      <path d="M12 3v18M3 10l9-7 9 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
      <path d="M5 14l-3 6h6l-3-6zM19 14l-3 6h6l-3-6z" fill="currentColor" opacity="0.7" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>`,
    /* ── About icons ─────────────────────────────────────────────── */
    handshake: `
      <path d="M9 11L7 9l-5 2v8h5v-3l4 3h5l4-5-5-5-3 1z" fill="currentColor" opacity="0.15" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
      <path d="M15 9l-3 2-3-2M7 9l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
      <path d="M14 9h4l1 1v3l-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    medal: `
      <circle cx="12" cy="8" r="5" fill="currentColor" opacity="0.18" stroke="currentColor" stroke-width="2"/>
      <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
      <path d="M12 6l1 2H9l1-2" fill="currentColor" opacity="0.6"/>`,
    target: `
      <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="currentColor" opacity="0.06"/>
      <circle cx="12" cy="12" r="6" stroke="currentColor" stroke-width="2" fill="none"/>
      <circle cx="12" cy="12" r="2.5" fill="currentColor"/>`,
    compass: `
      <circle cx="12" cy="12" r="10" fill="currentColor" opacity="0.06" stroke="currentColor" stroke-width="2"/>
      <polygon points="12 2 13.5 10.5 22 12 13.5 13.5 12 22 10.5 13.5 2 12 10.5 10.5" fill="currentColor" opacity="0.75"/>`,
    /* ── Contact icons ───────────────────────────────────────────── */
    location: `
      <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z" fill="currentColor" opacity="0.15" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
      <circle cx="12" cy="10" r="3" fill="currentColor"/>`,
    envelope: `
      <rect x="2" y="4" width="20" height="16" rx="3" fill="currentColor" opacity="0.1" stroke="currentColor" stroke-width="2"/>
      <polyline points="2 7 12 13 22 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    clock: `
      <circle cx="12" cy="12" r="10" fill="currentColor" opacity="0.08" stroke="currentColor" stroke-width="2"/>
      <polyline points="12 6 12 12 16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    whatsapp: `
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" fill="currentColor"/>
      <path d="M11.5 2.5C6.254 2.5 2 6.754 2 12c0 1.852.523 3.583 1.43 5.057L2 21.5l4.557-1.41A9.456 9.456 0 0011.5 21.5c5.246 0 9.5-4.254 9.5-9.5S16.746 2.5 11.5 2.5z" fill="currentColor" opacity="0.12" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>`,
    /* ── UI / CTA ────────────────────────────────────────────────── */
    'paper-plane': `
      <polygon points="22 2 15 22 11 13 2 9 22 2" fill="currentColor" opacity="0.85" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
      <line x1="22" y1="2" x2="11" y2="13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>`,
    check: `
      <polyline points="20 6 9 17 4 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    warning: `
      <circle cx="12" cy="12" r="10" fill="currentColor" opacity="0.1" stroke="currentColor" stroke-width="2"/>
      <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <line x1="12" y1="16" x2="12.01" y2="16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>`,
    briefcase: `
      <rect x="2" y="7" width="20" height="14" rx="2" fill="currentColor" opacity="0.1" stroke="currentColor" stroke-width="2"/>
      <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
      <line x1="2" y1="12" x2="22" y2="12" stroke="currentColor" stroke-width="2"/>`,
    star: `
      <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" fill="currentColor" opacity="0.9"/>`,
    arrow: `
      <line x1="5" y1="12" x2="19" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <polyline points="13 6 19 12 13 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    chat: `
      <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" fill="currentColor" opacity="0.12" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>`,
    sun: `
      <circle cx="12" cy="12" r="5" fill="currentColor" opacity="0.85"/>
      <line x1="12" y1="1" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <line x1="12" y1="21" x2="12" y2="23" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <line x1="1" y1="12" x2="3" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <line x1="21" y1="12" x2="23" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>`,
    moon: `
      <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" fill="currentColor" opacity="0.85"/>`,
    heart: `
      <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" fill="currentColor" opacity="0.8" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>`,
    home: `
      <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" fill="currentColor" opacity="0.12" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
      <polyline points="9 22 9 12 15 12 15 22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    building: `
      <rect x="4" y="2" width="16" height="20" rx="1" fill="currentColor" opacity="0.1" stroke="currentColor" stroke-width="2"/>
      <path d="M9 6h1M9 10h1M9 14h1M14 6h1M14 10h1M14 14h1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <rect x="9" y="18" width="6" height="4" fill="currentColor" opacity="0.7"/>`,
    newspaper: `
      <path d="M4 22h14a2 2 0 002-2V7.5L14.5 2H6a2 2 0 00-2 2v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="currentColor" fill-opacity="0.08"/>
      <polyline points="14 2 14 8 20 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
      <path d="M2 15h10M2 18h6M2 12h3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>`,
    instagram: `
      <rect x="2" y="2" width="20" height="20" rx="5" fill="currentColor" opacity="0.1" stroke="currentColor" stroke-width="2"/>
      <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2" fill="none"/>
      <circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/>`,
    linkedin: `
      <rect x="2" y="2" width="20" height="20" rx="3" fill="currentColor" opacity="0.1" stroke="currentColor" stroke-width="2"/>
      <line x1="8" y1="11" x2="8" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <line x1="8" y1="8" x2="8" y2="8.5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
      <path d="M12 17v-4a2 2 0 014 0v4M12 11v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>`,
    twitter: `
      <path d="M4 4l16 16M4 20L20 4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>`,
    'map-pin': `
      <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z" fill="currentColor" opacity="0.15" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
      <circle cx="12" cy="10" r="3" fill="currentColor"/>`,
    spinner: `
      <path d="M21 12a9 9 0 11-6.219-8.56" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" fill="none"/>`,
    chevron: `
      <polyline points="6 9 12 15 18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    'chevron-right': `
      <polyline points="9 18 15 12 9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    'arrow-left': `
      <line x1="19" y1="12" x2="5" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <polyline points="11 18 5 12 11 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    'arrow-up-right': `
      <line x1="7" y1="17" x2="17" y2="7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <polyline points="7 7 17 7 17 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>`,
    lock: `
      <rect x="3" y="11" width="18" height="11" rx="2" fill="currentColor" opacity="0.15" stroke="currentColor" stroke-width="2"/>
      <path d="M7 11V7a5 5 0 0110 0v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>`,
  };
</script>

<svg
  width={size}
  height={size}
  viewBox="0 0 24 24"
  fill="none"
  xmlns="http://www.w3.org/2000/svg"
  class={cls}
  stroke={variant === 'outline' ? 'currentColor' : undefined}
  stroke-width={variant === 'outline' ? 1.6 : undefined}
  stroke-linecap="round"
  stroke-linejoin="round"
  aria-hidden="true"
>
  {@html (variant === 'outline' ? outlinePaths[name] : null) || paths[name] || ''}
</svg>
