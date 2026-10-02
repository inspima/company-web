<script>
  import Icon from '../lib/Icon.svelte';

  export let config;
  export let services = [];
  export let metrics = [];
  export let projects = [];
  export let faqs = [];
  export let testimonial = null;

  let openFaq = -1;
</script>

<div class="service-page service-{config.color}">
  <section class="service-hero">
    <div class="page-container hero-grid">
      <div class="hero-copy">
        <nav class="pillar-nav" aria-label="Pilar layanan">
          {#each ['build', 'rescue', 'boost'] as pillar}
            <a href="/{pillar}" aria-current={pillar === config.name ? 'page' : undefined}>{pillar}</a>
          {/each}
        </nav>
        <p class="eyebrow"><span></span> PILAR {config.name.toUpperCase()}</p>
        <h1 class="hero-heading">{config.heading}<span class="hero-accent">{config.accent}</span></h1>
        <p class="hero-description">{config.description}</p>
        <div class="hero-actions">
          <a class="service-button primary" href={config.primaryHref} target={config.name === 'rescue' ? '_blank' : undefined} rel={config.name === 'rescue' ? 'noopener' : undefined}>
            {config.primary} <Icon name="arrow-up-right" size={16} />
          </a>
          <a class="service-button secondary" href={config.secondaryHref}>{config.secondary} <Icon name="arrow" size={15} /></a>
        </div>
      </div>
      <aside class="approach-panel" aria-label="Pendekatan layanan">
        <div class="panel-top"><span>THE {config.name.toUpperCase()} APPROACH</span><span class="panel-mark" aria-hidden="true">{config.name === 'build' ? '01' : config.name === 'rescue' ? '02' : '03'}</span></div>
        <div class="panel-body">
          <h2>{config.panel}</h2>
          <ol class="approach-steps">
            {#each config.steps as step, i}
              <li><span class="step-number">0{i + 1}</span><div><h3>{step[0]}</h3><p>{step[1]}</p></div></li>
            {/each}
          </ol>
        </div>
        <p class="panel-note">{config.note}</p>
      </aside>
    </div>
  </section>

  {#if metrics.length}
    <section class="service-metrics" aria-label="Ringkasan layanan">
      <div class="page-container metrics-grid" style="--metric-count: {metrics.length}">
        {#each metrics as metric}<div class="metric"><strong>{metric.v}</strong><span>{metric.l}</span></div>{/each}
      </div>
    </section>
  {/if}

  <section class="service-section">
    <div class="page-container">
      <div class="section-header"><p class="eyebrow">LAYANAN {config.name.toUpperCase()}</p><h2>{config.section}</h2><p>{config.sub}</p></div>
      <div class="service-grid">
        {#each services as service, i}
          <article class="service-card">
            <div class="card-top"><span class="service-icon"><Icon name={service.icon} size={20} /></span><span class="card-number">0{i + 1}</span></div>
            <h3>{service.title}</h3><p>{service.desc}</p>
          </article>
        {/each}
      </div>
    </div>
  </section>

  {#if projects.length}
    <section class="service-section alternate">
      <div class="page-container">
        <div class="section-header project-heading"><div><p class="eyebrow">SELECTED WORK</p><h2>Proyek {config.name.toUpperCase()} Terbaru</h2></div><a class="service-button secondary" href="/projects">Semua proyek <Icon name="arrow-up-right" size={15} /></a></div>
        <div class="service-grid">
          {#each projects as project}
            <a href="/project/{project.slug}" class="project-card">
              <div class="project-image">{#if project.image_url}<img src={project.image_url} alt={project.title} loading="lazy" />{:else}<Icon name="layers" size={40} />{/if}</div>
              <div class="project-copy"><h3>{project.title}<Icon name="arrow-up-right" size={16} /></h3><p class="line-clamp-2">{project.description_short || ''}</p></div>
            </a>
          {/each}
        </div>
      </div>
    </section>
  {/if}

  {#if testimonial}
    <section class="service-section alternate">
      <div class="page-container testimonial-layout">
        <div><p class="eyebrow">CLIENT EXPERIENCE</p><h2>Hasil yang dirasakan<br />oleh klien kami.</h2></div>
        <figure class="testimonial">
          <div class="quote-mark" aria-hidden="true">“</div>
          <blockquote>{testimonial.quote}</blockquote>
          <figcaption><span class="client-initial">{testimonial.name.charAt(0)}</span><div><strong>{testimonial.name}</strong><p>{testimonial.role}</p></div></figcaption>
        </figure>
      </div>
    </section>
  {/if}

  {#if faqs.length}
    <section class="service-section alternate">
      <div class="page-container faq-layout">
        <div class="section-header"><p class="eyebrow">FAQ</p><h2>Sebelum kita<br />mulai bekerja sama.</h2><p>Jawaban untuk pertanyaan yang sering kami terima.</p></div>
        <div class="faq-list">
          {#each faqs as faq, i}
            <div class="faq-item">
              <h3><button id="{config.name}-faq-question-{i}" aria-expanded={openFaq === i} aria-controls="{config.name}-faq-answer-{i}" on:click={() => openFaq = openFaq === i ? -1 : i}>{faq.q}<span class:expanded={openFaq === i}><Icon name="chevron" size={16} /></span></button></h3>
              <div id="{config.name}-faq-answer-{i}" role="region" aria-labelledby="{config.name}-faq-question-{i}" hidden={openFaq !== i}><p>{faq.a}</p></div>
            </div>
          {/each}
        </div>
      </div>
    </section>
  {/if}
</div>

<style>
  .service-page { --service-accent: #2556d8; --service-soft: #eef3ff; --service-hover: #1d46b7; }
  .service-teal { --service-accent: #0e8176; --service-soft: #edf8f5; --service-hover: #09675e; }
  .service-orange { --service-accent: #b65418; --service-soft: #fff5ed; --service-hover: #944210; }
  .page-container { max-width: 76rem; margin: 0 auto; padding: 0 2rem; }
  .service-hero { padding: 6.75rem 0 3.5rem; background: #f8fafc; position: relative; }
  .service-hero::before { content: ''; position: absolute; inset: 0 0 0 50%; pointer-events: none; background-image: linear-gradient(#e5ebf3 1px, transparent 1px), linear-gradient(90deg, #e5ebf3 1px, transparent 1px); background-size: 36px 36px; mask-image: radial-gradient(ellipse at center, #000, transparent 70%); opacity: .65; }
  .hero-grid { display: grid; grid-template-columns: 1.15fr 1fr; gap: 4rem; align-items: center; position: relative; }
  .hero-copy { min-width: 0; }
  .pillar-nav { display: inline-flex; gap: .25rem; padding: .25rem; border: 1px solid rgb(var(--color-border)); border-radius: 8px; background: #fff; margin-bottom: 1.75rem; }
  .pillar-nav a { padding: .45rem .85rem; font-size: .6rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: rgb(var(--color-text-sec)); border-radius: 5px; transition: background .2s, color .2s; }
  .pillar-nav a:hover { color: var(--service-accent); }
  .pillar-nav a[aria-current='page'] { background: var(--service-soft); color: var(--service-accent); }
  .eyebrow { color: var(--service-accent); font-size: .6rem; letter-spacing: .14em; font-weight: 700; margin-bottom: .85rem; display: flex; align-items: center; gap: .6rem; }
  .eyebrow span { width: 20px; height: 2px; background: var(--service-accent); }
  .hero-copy :global(.hero-heading) { font-size: clamp(2rem, 3.5vw, 3.1rem); line-height: 1.18; margin-bottom: 1.15rem; }
  .hero-accent { display: block; color: var(--service-accent); }
  .hero-description { font-size: .9rem; line-height: 1.85; color: rgb(var(--color-text-sec)); max-width: 33rem; margin-bottom: 1.5rem; }
  .hero-actions { display: flex; gap: .65rem; flex-wrap: wrap; }
  .service-button { display: inline-flex; justify-content: center; align-items: center; gap: .75rem; padding: .8rem 1.15rem; border-radius: 8px; font-size: .8rem; font-weight: 600; border: 1px solid; transition: background .2s, border-color .2s, color .2s; }
  .primary { background: var(--service-accent); color: #fff; border-color: var(--service-accent); }
  .primary:hover { background: var(--service-hover); border-color: var(--service-hover); }
  .secondary { background: #fff; color: rgb(var(--color-text-main)); border-color: rgb(var(--color-border)); }
  .secondary:hover { color: var(--service-accent); border-color: var(--service-accent); }
  .approach-panel { min-width: 0; background: #fff; border: 1px solid #dfe6ef; border-radius: 12px; overflow: hidden; box-shadow: 0 16px 40px rgb(20 34 56 / .055); }
  .panel-top { padding: .9rem 1.5rem; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; border-bottom: 1px solid rgb(var(--color-border)); font-size: .6rem; letter-spacing: .12em; font-weight: 600; color: rgb(var(--color-text-sec)); }
  .panel-mark { color: var(--service-accent); letter-spacing: 0; font-size: .8rem; }
  .panel-body { padding: 1.5rem; }
  .panel-body h2 { font-size: 1.2rem; font-weight: 700; letter-spacing: -.03em; max-width: 19rem; line-height: 1.45; margin-bottom: 1.5rem; }
  .approach-steps { display: grid; gap: 1.25rem; }
  .approach-steps li { display: flex; align-items: flex-start; gap: 1rem; }
  .step-number { display: flex; align-items: center; justify-content: center; flex-shrink: 0; width: 32px; height: 32px; border: 1px solid rgb(var(--color-border)); border-radius: 8px; color: var(--service-accent); font-size: .65rem; font-weight: 600; }
  .approach-steps h3 { font-size: .8rem; font-weight: 600; margin-bottom: .25rem; }
  .approach-steps p { font-size: .75rem; line-height: 1.7; color: rgb(var(--color-text-sec)); }
  .panel-note { padding: .85rem 1.5rem; background: var(--service-soft); color: var(--service-accent); font-size: .65rem; font-weight: 500; }
  .service-metrics { background: #fff; border-block: 1px solid rgb(var(--color-border)); }
  .metrics-grid { display: grid; grid-template-columns: repeat(var(--metric-count), minmax(0, 1fr)); padding-block: 1.5rem; }
  .metric { display: flex; flex-direction: column; align-items: center; gap: .3rem; text-align: center; padding: .3rem 1rem; }
  .metric + .metric { border-left: 1px solid rgb(var(--color-border)); }
  .metric strong { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.75rem; letter-spacing: -.05em; color: var(--service-accent); }
  .metric span { font-size: .65rem; color: rgb(var(--color-text-sec)); }
  .service-section { padding: 3.75rem 0; background: #fff; }
  .alternate { background: #f8fafc; border-top: 1px solid rgb(var(--color-border)); }
  .section-header { margin-bottom: 1.75rem; }
  .section-header h2, .testimonial-layout h2 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(1.5rem, 2.5vw, 2rem); font-weight: 800; letter-spacing: -.04em; line-height: 1.35; margin-bottom: .75rem; }
  .section-header > p:last-child { font-size: .85rem; line-height: 1.8; color: rgb(var(--color-text-sec)); max-width: 38rem; }
  .service-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.25rem; }
  .service-card { padding: 1.5rem; border: 1px solid rgb(var(--color-border)); border-radius: 12px; background: #fff; transition: border-color .2s, box-shadow .2s; }
  .service-card:hover { border-color: var(--service-accent); box-shadow: 0 8px 24px rgb(20 34 56 / .04); }
  .card-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
  .service-icon { width: 40px; height: 40px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; color: var(--service-accent); background: var(--service-soft); }
  .card-number { color: #748198; font-size: .65rem; }
  .service-card h3, .project-copy h3 { font-size: .95rem; font-weight: 700; letter-spacing: -.02em; margin-bottom: .6rem; }
  .service-card p, .project-copy p { font-size: .8rem; line-height: 1.8; color: rgb(var(--color-text-sec)); }
  .project-heading { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
  .project-heading h2 { margin-bottom: 0; }
  .project-card { overflow: hidden; border: 1px solid rgb(var(--color-border)); border-radius: 12px; background: #fff; }
  .project-image { height: 200px; background: var(--service-soft); color: var(--service-accent); display: flex; justify-content: center; align-items: center; overflow: hidden; }
  .project-image img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
  .project-card:hover img { transform: scale(1.03); }
  .project-card:hover h3 { color: var(--service-accent); }
  .project-copy { padding: 1.25rem; }
  .project-copy h3 { display: flex; justify-content: space-between; align-items: center; gap: .5rem; }
  .project-copy h3 :global(svg) { flex-shrink: 0; }
  .testimonial-layout, .faq-layout { display: grid; grid-template-columns: 1fr 1.7fr; gap: 4rem; align-items: start; }
  .testimonial { background: #fff; border: 1px solid rgb(var(--color-border)); border-radius: 12px; padding: 1.75rem 2rem; }
  .quote-mark { font-family: Georgia, serif; font-size: 3rem; line-height: 1; color: var(--service-accent); }
  blockquote { font-size: 1rem; line-height: 1.85; margin-bottom: 1.5rem; }
  figcaption { display: flex; align-items: center; gap: .75rem; }
  .client-initial { display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: var(--service-soft); color: var(--service-accent); font-size: .8rem; font-weight: 700; }
  figcaption strong { font-size: .8rem; }
  figcaption p { font-size: .7rem; color: rgb(var(--color-text-sec)); margin-top: .1rem; }
  .faq-list { border-top: 1px solid rgb(var(--color-border)); }
  .faq-item { border-bottom: 1px solid rgb(var(--color-border)); }
  .faq-item button { width: 100%; display: flex; justify-content: space-between; align-items: center; gap: 1rem; text-align: left; padding: 1.2rem 0; font-size: .85rem; font-weight: 600; }
  .faq-item button:hover { color: var(--service-accent); }
  .faq-item button span { color: var(--service-accent); flex-shrink: 0; transition: transform .2s; }
  .faq-item button .expanded { transform: rotate(180deg); }
  .faq-item div p { font-size: .8rem; line-height: 1.8; color: rgb(var(--color-text-sec)); padding-bottom: 1.2rem; }
  @media (max-width: 1023px) {
    .hero-grid { gap: 2rem; }
    .hero-copy :global(.hero-heading) { font-size: 2.25rem; }
    .testimonial-layout, .faq-layout { gap: 2rem; }
  }
  @media (max-width: 767px) {
    .page-container { padding-inline: 1.5rem; }
    .hero-grid { grid-template-columns: 1fr; gap: 2rem; }
    .service-hero { padding: 6rem 0 2.75rem; }
    .service-hero::before { inset: 40% 0 0; }
    .hero-copy :global(.hero-heading) { font-size: clamp(1.9rem, 6vw, 2.5rem); }
    .approach-panel { width: 100%; }
    .service-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .testimonial-layout, .faq-layout { grid-template-columns: 1fr; gap: 1.5rem; }
    .faq-layout .section-header { margin-bottom: 0; }
    .service-section { padding: 2.75rem 0; }
    .metrics-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem 0; }
    .metric:nth-child(3) { border-left: 0; }
    .metric:last-child:nth-child(odd) { grid-column: 1 / -1; }
    .project-heading { align-items: flex-start; flex-direction: column; }
  }
  @media (max-width: 479px) {
    .page-container { padding-inline: 1rem; }
    .service-grid { grid-template-columns: 1fr; }
    .hero-description { font-size: .85rem; }
    .service-button { padding: .75rem .9rem; font-size: .75rem; }
    .panel-top, .panel-body, .panel-note { padding-inline: 1.25rem; }
    .testimonial { padding: 1.5rem; }
    blockquote { font-size: .9rem; }
    .metric strong { font-size: 1.5rem; }
  }
</style>
