const puppeteer = require('puppeteer');
const path = require('path');

const htmlContent = `<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  @page { margin: 40px 50px; }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; color: #1a1a1a; line-height: 1.6; font-size: 11px; }
  
  .cover { page-break-after: always; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; text-align: center; }
  .cover h1 { font-size: 28px; font-weight: 300; color: #2d3b38; margin-bottom: 8px; }
  .cover h2 { font-size: 16px; font-weight: 400; color: #5a7a74; margin-bottom: 40px; }
  .cover .meta { font-size: 11px; color: #888; }
  .cover .line { width: 60px; height: 2px; background: #a3b8b4; margin: 24px auto; }
  
  h1 { font-size: 18px; font-weight: 500; color: #2d3b38; margin: 28px 0 12px 0; padding-bottom: 6px; border-bottom: 1px solid #e0e0e0; }
  h2 { font-size: 14px; font-weight: 500; color: #3d5a54; margin: 20px 0 8px 0; }
  h3 { font-size: 12px; font-weight: 500; color: #4a6b64; margin: 14px 0 6px 0; }
  
  p { margin-bottom: 8px; }
  ul { margin: 6px 0 12px 20px; }
  li { margin-bottom: 4px; }
  
  .score-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin: 16px 0; }
  .score-card { border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; text-align: center; }
  .score-card .label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #888; margin-bottom: 4px; }
  .score-card .score { font-size: 18px; font-weight: 500; }
  .score-card .score.low { color: #dc2626; }
  .score-card .score.mid { color: #d97706; }
  .score-card .score.high { color: #16a34a; }
  
  table { width: 100%; border-collapse: collapse; margin: 10px 0 16px 0; font-size: 10.5px; }
  th, td { padding: 7px 10px; text-align: left; border-bottom: 1px solid #eee; }
  th { background: #f7faf9; font-weight: 500; color: #3d5a54; font-size: 10px; text-transform: uppercase; letter-spacing: 0.3px; }
  tr:last-child td { border-bottom: none; }
  
  .issue-item { background: #fafafa; border: 1px solid #eee; border-radius: 4px; padding: 8px 10px; margin-bottom: 6px; }
  .issue-item .severity { display: inline-block; font-size: 9px; font-weight: 600; padding: 2px 6px; border-radius: 3px; margin-right: 6px; text-transform: uppercase; color: white; }
  .severity.critical { background: #dc2626; }
  .severity.high { background: #d97706; }
  .severity.medium { background: #2563eb; }
  
  .phase { background: #f7faf9; border: 1px solid #d4e4df; border-radius: 6px; padding: 14px; margin-bottom: 12px; }
  .phase h3 { margin-top: 0; color: #2d3b38; }
  
  .section-break { page-break-before: always; }
  
  .flow-box { background: #f0f5f4; border-radius: 4px; padding: 10px 14px; margin: 8px 0; font-family: 'Consolas', monospace; font-size: 10px; color: #3d5a54; }
  
  .summary-box { background: #2d3b38; color: white; border-radius: 6px; padding: 16px; margin-top: 20px; }
  .summary-box h2 { color: #a3b8b4; margin-top: 0; }
  .summary-box p { color: #d4e4df; }
  .summary-box ul { color: #d4e4df; }
</style>
</head>
<body>

<!-- COVER PAGE -->
<div class="cover">
  <h1>UX & Conversion Audit</h1>
  <h2>therapistlysander.com</h2>
  <div class="line"></div>
  <p style="font-size: 12px; color: #5a7a74; max-width: 400px;">Emotional UX, Trauma-Informed Design & Premium Conversion Analysis for a Psychotherapy Practice</p>
  <div class="meta" style="margin-top: 60px;">
    <p>Prepared: May 2025</p>
    <p>Target audiences: Trauma therapy clients | Private-pay clients | Expats/International clients</p>
  </div>
  <div style="margin-top: 50px; padding: 16px 24px; border: 1px solid #d4e4df; border-radius: 6px; text-align: center;">
    <p style="font-size: 11px; font-weight: 500; color: #2d3b38; margin-bottom: 4px;">Prepared by</p>
    <p style="font-size: 14px; font-weight: 500; color: #2d3b38; margin-bottom: 2px;">Rakesh Maity</p>
    <p style="font-size: 10px; color: #5a7a74; margin-bottom: 2px;">Senior Software Engineer</p>
    <p style="font-size: 10px; color: #5a7a74;">Custom Laravel Development | Scalable Web Systems</p>
  </div>
</div>

<!-- EXECUTIVE SUMMARY -->
<h1>Executive Summary</h1>
<p>This audit evaluates therapistlysander.com across emotional UX, conversion architecture, premium positioning, and trauma-informed design principles. The site has a solid therapeutic philosophy and genuine personal narrative, but is undermined by structural conversion failures, insufficient trust signals, and visual execution that reads as DIY rather than premium.</p>

<div class="score-grid">
  <div class="score-card"><div class="label">Emotional Safety</div><div class="score mid">5.5/10</div></div>
  <div class="score-card"><div class="label">Conversion</div><div class="score low">4/10</div></div>
  <div class="score-card"><div class="label">Premium Feel</div><div class="score mid">5/10</div></div>
  <div class="score-card"><div class="label">Mobile UX</div><div class="score mid">6/10</div></div>
  <div class="score-card"><div class="label">Typography</div><div class="score mid">5.5/10</div></div>
  <div class="score-card"><div class="label">Trust Signals</div><div class="score low">4.5/10</div></div>
</div>

<!-- SECTION 1 -->
<h1>1. Main Website Problems</h1>

<table>
  <tr><th>Problem</th><th>Impact</th></tr>
  <tr><td>No above-the-fold CTA</td><td>Visitors must scroll entire page to find booking options</td></tr>
  <tr><td>Gmail address as contact</td><td>Immediately kills premium positioning</td></tr>
  <tr><td>Single testimonial only</td><td>Woefully insufficient for private-pay conversion</td></tr>
  <tr><td>AI-generated imagery (ChatGPT graphic)</td><td>Signals low brand investment</td></tr>
  <tr><td>Wix platform limitations</td><td>Default blue links, limited design control, footer attribution</td></tr>
  <tr><td>Text-wall structure</td><td>No visual chunking, icons, or breathing room</td></tr>
  <tr><td>No booking system</td><td>WhatsApp/email is high-friction for anxious clients</td></tr>
  <tr><td>Dutch language bleed</td><td>Skip link in Dutch breaks English-only intent</td></tr>
</table>

<!-- SECTION 2 -->
<h1 class="section-break">2. Emotional & Psychological UX Issues</h1>

<h2>Trauma-Informed Design Failures</h2>

<div class="issue-item"><span class="severity critical">Critical</span> Dense paragraph blocks without visual breaks force trauma clients into sustained reading &mdash; the exact thing they struggle with when dysregulated. Trauma clients scan; they don't read walls of text.</div>

<div class="issue-item"><span class="severity critical">Critical</span> No "what happens next" clarity. When someone with anxiety decides to reach out, ambiguity about the process creates anticipatory dread.</div>

<div class="issue-item"><span class="severity high">High</span> Justified text alignment creates uneven word spacing that subtly disrupts reading flow &mdash; the micro-level equivalent of unpredictability.</div>

<div class="issue-item"><span class="severity high">High</span> Full-body portrait in hero (1919px tall) feels more "model" than "safe person." A warmer, cropped shoulders-up image with natural lighting would create more intimacy.</div>

<div class="issue-item"><span class="severity high">High</span> No explicit safety signals: no mention of confidentiality, no "you're in control of the pace," no acknowledgment that reaching out is hard.</div>

<div class="issue-item"><span class="severity medium">Medium</span> Emotional language before relational trust. The site jumps into deep therapeutic language before establishing who this person is as a human.</div>

<h2>Core Principle Violated</h2>
<p>The first emotional beat should be: <strong>"You're safe here. Here's what to expect."</strong> Not: "Here's my clinical methodology." Front-load warmth and containment.</p>

<!-- SECTION 3 -->
<h1 class="section-break">3. Mobile Optimization Improvements</h1>

<div class="issue-item"><span class="severity critical">Critical</span> No sticky CTA &mdash; contact section is 8-10 screen-scrolls deep on mobile. A floating "Book a Free Consultation" button is essential.</div>

<div class="issue-item"><span class="severity high">High</span> Hero image (610x1919px) will dominate mobile viewport, pushing all content far below the fold.</div>

<div class="issue-item"><span class="severity high">High</span> Touch targets for WhatsApp and email need minimum 48px with adequate spacing between them.</div>

<div class="issue-item"><span class="severity medium">Medium</span> Content prioritization: mobile should lead with 2-line value prop then immediate CTA. Methodology belongs below or on subpage.</div>

<div class="issue-item"><span class="severity medium">Medium</span> Navigation needs a pinned "Book Now" CTA in the mobile header bar.</div>

<h2>Recommended Mobile Hierarchy</h2>
<ul>
  <li>Warm portrait (cropped, max 40vh)</li>
  <li>1-sentence value proposition</li>
  <li>Primary CTA button</li>
  <li>"Does this sound like you?" validation section</li>
  <li>Brief credentials</li>
  <li>Testimonial snippet</li>
  <li>Floating sticky CTA throughout scroll</li>
</ul>

<!-- SECTION 4 -->
<h1 class="section-break">4. Typography & Spacing Recommendations</h1>

<h2>Current State</h2>
<p>Futura Light at 60px for H1, 24px subheadings. Body defaults to system sans-serif. Justified alignment throughout.</p>

<h2>Problems Identified</h2>
<ul>
  <li>Futura Light at display size feels cold and architectural &mdash; not warm/therapeutic</li>
  <li>Justified body text creates rivers of whitespace</li>
  <li>No typographic rhythm (inconsistent section spacing)</li>
  <li>Line height and letter spacing not optimized for readability</li>
  <li>Content width unrestricted &mdash; lines too long for comfortable reading</li>
</ul>

<h2>Recommended Changes</h2>
<table>
  <tr><th>Element</th><th>Current</th><th>Recommended</th></tr>
  <tr><td>Heading font</td><td>Futura Light</td><td>Warmer serif (Cormorant Garamond, Freight Display) or rounded sans (Circular, Sofia Pro)</td></tr>
  <tr><td>Body font</td><td>System sans-serif</td><td>Inter, DM Sans, or Outfit</td></tr>
  <tr><td>H1 size</td><td>60px</td><td>42-48px, medium weight</td></tr>
  <tr><td>Body alignment</td><td>Justified</td><td>Left-aligned</td></tr>
  <tr><td>Line height</td><td>Default (~1.4)</td><td>1.7-1.8 for body text</td></tr>
  <tr><td>Paragraph spacing</td><td>Minimal</td><td>24-32px between paragraphs</td></tr>
  <tr><td>Section spacing</td><td>Inconsistent</td><td>80-120px between major sections</td></tr>
  <tr><td>Max content width</td><td>Full-width</td><td>640-720px text column</td></tr>
</table>

<h2>Typography Psychology</h2>
<p>For trauma therapy audiences, typography should feel: unhurried, spacious, gentle, and clear. Avoid sharp geometric fonts (they feel clinical). Prefer fonts with humanist proportions &mdash; rounded terminals, open apertures, comfortable x-heights. The goal is a reading experience that mirrors the therapeutic relationship: calm, spacious, and unhurried.</p>

<!-- SECTION 5 -->
<h1 class="section-break">5. Conversion & Lead-Generation Improvements</h1>

<h2>Current Conversion Architecture (Score: 3/10)</h2>
<div class="flow-box">Visitor &rarr; Scroll entire page &rarr; Find WhatsApp/Email at bottom &rarr; Compose message &rarr; Wait for reply &rarr; ??</div>

<h2>Recommended Conversion Architecture</h2>
<div class="flow-box">Visitor &rarr; Click "Book Free Consultation" (visible immediately) &rarr; Choose time slot (Cal.com/Calendly) &rarr; Brief intake form &rarr; Confirmation email &rarr; 15-min call &rarr; First session</div>

<h2>Key Conversion Recommendations</h2>
<ul>
  <li><strong>Offer a free 15-minute consultation call</strong> &mdash; industry standard for private-pay therapy. Lowers commitment threshold dramatically.</li>
  <li><strong>Add a sticky CTA</strong> reading "Book a Free 15-Minute Call" &mdash; visible at every scroll position.</li>
  <li><strong>Replace WhatsApp as primary CTA</strong> with a scheduling tool (Cal.com is free, clean, professional). Keep WhatsApp as secondary channel.</li>
  <li><strong>Add "What Happens When You Reach Out" section</strong> with clear numbered steps.</li>
  <li><strong>Add mid-page micro-commitments</strong>: "Does this sound like you?" checklist, anchor links to booking.</li>
  <li><strong>Address objections explicitly</strong>: Cost, "Am I bad enough?", time commitment, "Will it work?"</li>
  <li><strong>Add scarcity signal</strong>: "Currently accepting X new clients per month" or "Limited availability."</li>
</ul>

<h2>Missing Conversion Elements</h2>
<table>
  <tr><th>Element</th><th>Impact on Conversion</th></tr>
  <tr><td>Free consultation offer</td><td>Can increase bookings 40-60%</td></tr>
  <tr><td>Pricing transparency (range)</td><td>Reduces anxiety, qualifies leads</td></tr>
  <tr><td>Scheduling tool</td><td>Removes "compose a message" friction</td></tr>
  <tr><td>FAQ section</td><td>Handles objections, reduces bounce</td></tr>
  <tr><td>Multiple testimonials</td><td>Social proof drives conversion 20-35%</td></tr>
  <tr><td>Process explanation</td><td>Reduces unknown-fear for anxious clients</td></tr>
</table>

<!-- SECTION 6 -->
<h1 class="section-break">6. Homepage Refinement Ideas</h1>

<h2>Current Structure (Problematic)</h2>
<p>Hero &rarr; Dense about text &rarr; Quote &rarr; Methods list &rarr; Trauma explanation &rarr; Conditions &rarr; 1 Testimonial &rarr; Contact</p>

<h2>Recommended Structure</h2>
<table>
  <tr><th>#</th><th>Section</th><th>Purpose</th></tr>
  <tr><td>1</td><td>Hero: Warm portrait + 1-sentence value prop + CTA</td><td>Immediate clarity and action path</td></tr>
  <tr><td>2</td><td>Validation: "If you're feeling..." (3-4 pain points)</td><td>Emotional recognition / "you get me"</td></tr>
  <tr><td>3</td><td>About Lysander: 3-4 sentences + link to full bio</td><td>Human connection and trust</td></tr>
  <tr><td>4</td><td>How I Work: Visual 3-step process</td><td>Remove ambiguity and fear of unknown</td></tr>
  <tr><td>5</td><td>Specializations: Icon grid (max 6)</td><td>Credibility and relevance confirmation</td></tr>
  <tr><td>6</td><td>Testimonials: 3 rotating quotes</td><td>Social proof and outcome visualization</td></tr>
  <tr><td>7</td><td>"What to Expect" step-by-step</td><td>Process clarity for anxious visitors</td></tr>
  <tr><td>8</td><td>FAQ: 4-5 common questions</td><td>Objection handling</td></tr>
  <tr><td>9</td><td>Final CTA: Full-width calm section</td><td>Conversion capture</td></tr>
  <tr><td>10</td><td>Footer: Credentials, privacy, contact</td><td>Professional completion</td></tr>
</table>

<h2>Key Principles for Homepage</h2>
<ul>
  <li>Lead with empathy, not credentials</li>
  <li>Maximum 2-3 sentences per text block</li>
  <li>Every section should have a visual element (icon, image, or whitespace)</li>
  <li>CTA should appear minimum 3 times on the page</li>
  <li>Progressive disclosure: overview on homepage, depth on subpages</li>
</ul>

<!-- SECTION 7 -->
<h1 class="section-break">7. Booking Flow Improvements</h1>

<h2>Current Flow (High Friction)</h2>
<ul>
  <li>No structured booking path</li>
  <li>WhatsApp requires client to initiate and compose a message</li>
  <li>Email requires subject line creation and message drafting</li>
  <li>No response time expectation set</li>
  <li>No timezone clarity for international clients</li>
  <li>No intake information gathered upfront</li>
</ul>

<h2>Recommended Booking System</h2>
<ul>
  <li><strong>Tool:</strong> Cal.com (free tier), Calendly, or Acuity Scheduling</li>
  <li><strong>Offer:</strong> "Book a Free 15-Minute Discovery Call"</li>
  <li><strong>Intake form fields:</strong> Name, Email, Timezone, "What brings you to therapy?" (2-3 sentences max), Preferred session type (online/in-person)</li>
  <li><strong>Confirmation:</strong> Automated email with what to expect on the call</li>
  <li><strong>Follow-up:</strong> Automated reminder 24h before</li>
</ul>

<h2>Timezone Considerations (for Expat Clients)</h2>
<ul>
  <li>Display available hours in multiple timezones</li>
  <li>Clearly state "Sessions available across timezones" on homepage</li>
  <li>Scheduling tool should auto-detect visitor timezone</li>
  <li>State clearly: "Online sessions via secure video platform"</li>
</ul>

<h2>WhatsApp Optimization</h2>
<p>Keep WhatsApp as a secondary/preferred channel (especially for Asia-Pacific market) but pre-populate the message:</p>
<div class="flow-box">wa.me/66935309052?text=Hi%20Lysander%2C%20I'm%20interested%20in%20booking%20a%20consultation.%20My%20name%20is%20</div>
<p>This removes the "what do I say?" anxiety that prevents trauma clients from reaching out.</p>

<!-- SECTION 8 -->
<h1 class="section-break">8. Visual Polish Recommendations</h1>

<table>
  <tr><th>Area</th><th>Current Issue</th><th>Recommended Fix</th></tr>
  <tr><td>Color palette</td><td>Default blue links break sage-green palette</td><td>Unify all interactive elements to palette (sage, muted teal)</td></tr>
  <tr><td>Imagery</td><td>AI-generated ChatGPT image visible</td><td>Replace with professional photography or curated high-quality stock</td></tr>
  <tr><td>Office/space photos</td><td>Tiny 123px thumbnails</td><td>Full-width, aspirational images of the therapy space</td></tr>
  <tr><td>White space</td><td>Insufficient between sections</td><td>80-120px vertical rhythm between major sections</td></tr>
  <tr><td>Button design</td><td>Flat, low-contrast WhatsApp button</td><td>Higher contrast, larger padding (16px 32px), subtle hover state</td></tr>
  <tr><td>Wix attribution</td><td>"Built with Wix" in footer</td><td>Upgrade plan or migrate to remove</td></tr>
  <tr><td>Favicon/branding</td><td>Default or missing</td><td>Custom subtle monogram or mark</td></tr>
  <tr><td>Loading experience</td><td>Standard Wix loader</td><td>Custom smooth fade-in animation</td></tr>
  <tr><td>Scroll behavior</td><td>Abrupt section transitions</td><td>Subtle fade-in on scroll (IntersectionObserver)</td></tr>
</table>

<h2>Photography Direction</h2>
<ul>
  <li><strong>Portrait:</strong> Warm, natural light. Shoulders-up crop. Genuine expression (not posed smile). Neutral/earth-tone clothing.</li>
  <li><strong>Environment:</strong> Clean therapy space, warm textures, plants, natural materials. Shot with depth of field.</li>
  <li><strong>Details:</strong> Hands holding a cup, journal and pen, soft textile textures, natural light through window.</li>
  <li><strong>Avoid:</strong> Staged/stock feeling, harsh lighting, cluttered backgrounds, clinical settings.</li>
</ul>

<!-- SECTION 9 -->
<h1 class="section-break">9. Premium UI/UX Upgrade Strategy</h1>

<h2>Platform Migration: Wix to Custom Build</h2>
<p>To move from "competent Wix site" to "premium private-pay therapist brand," the recommended path is migrating from Wix to a <strong>custom-built website</strong> using a modern framework (Laravel + Blade/Livewire or a static-first approach).</p>

<h3>Why a Custom Build?</h3>
<ul>
  <li><strong>Full design control</strong> &mdash; pixel-perfect implementation of therapeutic aesthetics with no platform constraints</li>
  <li><strong>Performance</strong> &mdash; faster load times, optimized assets, no bloated platform JS</li>
  <li><strong>SEO ownership</strong> &mdash; complete control over meta, schema markup, sitemaps, and server-side rendering</li>
  <li><strong>No forced branding</strong> &mdash; no "Built with Wix" footer, no platform attribution</li>
  <li><strong>Scalability</strong> &mdash; blog, client portal, booking integration, multilingual support &mdash; all without platform limitations</li>
  <li><strong>Security &amp; compliance</strong> &mdash; proper data handling for therapy client inquiries (GDPR-ready)</li>
</ul>

<p><strong>Why Wix must go:</strong> Wix structurally limits premium perception through forced footer attribution, limited typography control, template-feeling layouts, slower load times, and inability to implement custom booking flows or advanced interactions.</p>

<h2>Brand System Requirements</h2>
<ul>
  <li>Custom domain email (lysander@therapistlysander.com)</li>
  <li>Consistent color palette applied to every element (no default blues)</li>
  <li>Professional photography session (portrait + therapy space + details)</li>
  <li>Subtle animation language (fade-ins, smooth transitions)</li>
  <li>Custom 404 page</li>
  <li>Privacy policy and terms page</li>
  <li>Proper SSL and professional hosting</li>
</ul>

<h2>Premium Signals to Add</h2>
<ul>
  <li>"Limited availability &mdash; currently accepting [X] new clients per month"</li>
  <li>Professional credentials with verification links</li>
  <li>Association memberships / certification logos</li>
  <li>Published articles or media features (if any)</li>
  <li>A "My Approach" page that reads as a mini-manifesto, not a CV</li>
  <li>Client outcome metrics or satisfaction rates</li>
</ul>

<h2>Design Language for Premium Therapy</h2>
<table>
  <tr><th>Element</th><th>Premium Signal</th></tr>
  <tr><td>Whitespace</td><td>Generous &mdash; signals confidence and intentionality</td></tr>
  <tr><td>Typography</td><td>Considered pairings &mdash; serif + sans or two complementary sans</td></tr>
  <tr><td>Color</td><td>Restrained palette (3-4 colors max) with one intentional accent</td></tr>
  <tr><td>Photography</td><td>Original, warm, high-quality &mdash; no stock feeling</td></tr>
  <tr><td>Micro-interactions</td><td>Subtle hover states, smooth transitions, thoughtful loading</td></tr>
  <tr><td>Content density</td><td>Less text, more space &mdash; every word earns its place</td></tr>
  <tr><td>Navigation</td><td>Minimal, clear, with persistent CTA</td></tr>
</table>

<!-- SECTION 10 -->
<h1 class="section-break">10. Step-by-Step Refinement Roadmap</h1>

<div class="phase">
  <h3>Phase 1: Quick Wins (Highest Impact)</h3>
  <ul>
    <li>Add a sticky/floating CTA button: "Book a Free Consultation"</li>
    <li>Set up Cal.com (free) or Calendly for scheduling</li>
    <li>Replace Gmail with custom domain email</li>
    <li>Remove or replace the AI-generated image</li>
    <li>Add 2-3 more testimonials (even anonymized with initials)</li>
    <li>Add a "What to Expect" section (3 clear steps)</li>
    <li>Pre-populate WhatsApp message link</li>
    <li>Fix blue default link colors to match palette</li>
  </ul>
</div>

<div class="phase">
  <h3>Phase 2: Content & Structure</h3>
  <ul>
    <li>Rewrite homepage following recommended section order</li>
    <li>Break all paragraph blocks into max 2-3 sentences with spacing</li>
    <li>Add a FAQ section (4-5 common questions)</li>
    <li>Create "How It Works" visual 3-step flow</li>
    <li>Add explicit safety/confidentiality statement</li>
    <li>Address pricing (range or "investment starts at...")</li>
    <li>Add "Does this sound like you?" validation section</li>
    <li>Create dedicated pages for each specialization (SEO benefit)</li>
  </ul>
</div>

<div class="phase">
  <h3>Phase 3: Design & Polish</h3>
  <ul>
    <li>Switch to warmer typography (serif headings + humanist sans body)</li>
    <li>Increase whitespace dramatically (80-120px between sections)</li>
    <li>Unify all colors &mdash; remove default blues</li>
    <li>Commission professional photography</li>
    <li>Add subtle scroll animations (fade-in sections)</li>
    <li>Remove Wix branding (upgrade plan)</li>
    <li>Implement consistent visual language across all pages</li>
    <li>Add custom favicon and Open Graph images</li>
  </ul>
</div>

<div class="phase">
  <h3>Phase 4: Platform & Premium (Custom Build)</h3>
  <ul>
    <li>Migrate from Wix to a custom-built website (Laravel / modern stack)</li>
    <li>Implement proper SEO (schema markup for therapist, local business)</li>
    <li>Add a blog/resources section (authority building + SEO)</li>
    <li>Create an international clients page (timezone info, language support)</li>
    <li>Add Google Reviews widget or Trustpilot integration</li>
    <li>Consider a 30-60 second intro video for hero section</li>
    <li>Implement analytics and conversion tracking</li>
    <li>A/B test CTA copy and placement</li>
  </ul>
</div>

<!-- FINAL SUMMARY -->
<div class="summary-box" style="margin-top: 40px;">
  <h2>Final Assessment</h2>
  <p style="margin-bottom: 12px;">The site has a solid therapeutic philosophy and genuine personal story, but it's undermined by:</p>
  <ul style="margin-left: 16px;">
    <li>Conversion architecture that requires too much initiative from anxious visitors</li>
    <li>Visual execution that reads as DIY rather than premium</li>
    <li>Content density that overwhelms rather than guides</li>
    <li>Trust gaps (single testimonial, Gmail, AI imagery) that don't match private-pay positioning</li>
  </ul>
  <p style="margin-top: 12px;">The core fix is structural: reduce words, increase whitespace, add a clear booking path visible at all times, and invest in the visual details that signal "this person takes their practice seriously enough to invest in it" &mdash; which is exactly what private-pay clients need to see before investing in you.</p>
</div>

<!-- NEXT STEPS / CONTACT -->
<div style="margin-top: 40px; page-break-before: always; text-align: center; padding-top: 60px;">
  <h1 style="border: none; text-align: center; font-size: 20px; color: #2d3b38;">Ready to Transform Your Website?</h1>
  <p style="font-size: 12px; color: #5a7a74; max-width: 480px; margin: 12px auto 30px auto;">I specialize in building custom, high-performance websites for professionals who need premium positioning and conversion-optimized experiences. Let's discuss how to bring this audit to life.</p>
  
  <div style="border: 1px solid #d4e4df; border-radius: 8px; padding: 24px; max-width: 420px; margin: 0 auto; text-align: left;">
    <p style="font-size: 15px; font-weight: 500; color: #2d3b38; margin-bottom: 4px;">Rakesh Maity</p>
    <p style="font-size: 11px; color: #5a7a74; margin-bottom: 16px;">Senior Software Engineer | Custom Laravel Development | Scalable Web Systems</p>
    
    <table style="width: 100%; font-size: 10.5px; margin: 0;">
      <tr><td style="padding: 5px 0; border: none; color: #888; width: 70px;">Email</td><td style="padding: 5px 0; border: none; color: #2d3b38;">rakesh.maity@zohomail.in</td></tr>
      <tr><td style="padding: 5px 0; border: none; color: #888;">WhatsApp</td><td style="padding: 5px 0; border: none; color: #2d3b38;">+91 9073090507</td></tr>
      <tr><td style="padding: 5px 0; border: none; color: #888;">Upwork</td><td style="padding: 5px 0; border: none; color: #3d5a54;">upwork.com/freelancers/rakeshmaity</td></tr>
      <tr><td style="padding: 5px 0; border: none; color: #888;">GitHub</td><td style="padding: 5px 0; border: none; color: #3d5a54;">github.com/rakeshmaity271</td></tr>
    </table>
  </div>
  
  <p style="font-size: 10px; color: #888; margin-top: 30px;">Custom-built websites &bull; Laravel &bull; Performance optimization &bull; SEO &bull; Conversion-focused design</p>
</div>

</body>
</html>`;

async function generatePDF() {
  const browser = await puppeteer.launch({ headless: true });
  const page = await browser.newPage();
  
  await page.setContent(htmlContent, { waitUntil: 'networkidle0' });
  
  const outputPath = path.join(__dirname, 'TherapistLysander-UX-Audit.pdf');
  
  await page.pdf({
    path: outputPath,
    format: 'A4',
    printBackground: true,
    margin: { top: '70px', right: '50px', bottom: '80px', left: '50px' },
    displayHeaderFooter: true,
    headerTemplate: `
      <div style="width: 100%; font-family: 'Segoe UI', Arial, sans-serif; font-size: 8px; padding: 0 50px; display: flex; justify-content: space-between; align-items: center; color: #3d5a54;">
        <span style="font-weight: 500;">Rakesh Maity | Senior Software Engineer</span>
        <span style="color: #888;">Custom Laravel Development | Scalable Web Systems</span>
      </div>
    `,
    footerTemplate: `
      <div style="width: 100%; font-family: 'Segoe UI', Arial, sans-serif; font-size: 7.5px; padding: 0 50px; border-top: 1px solid #e0e0e0; padding-top: 8px; display: flex; justify-content: space-between; align-items: center; color: #5a7a74;">
        <span>Email: rakesh.maity@zohomail.in | WhatsApp: +91 9073090507</span>
        <span>Upwork: upwork.com/freelancers/rakeshmaity | GitHub: github.com/rakeshmaity271</span>
      </div>
    `
  });
  
  await browser.close();
  console.log('PDF generated: ' + outputPath);
}

generatePDF().catch(err => {
  console.error('Error:', err);
  process.exit(1);
});
