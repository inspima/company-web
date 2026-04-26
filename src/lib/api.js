const BASE = '/api.php';

async function get(action, params = {}) {
  const qs = new URLSearchParams({ action, ...params });
  const res = await fetch(`${BASE}?${qs}`);
  if (!res.ok) throw new Error(`API error ${res.status}`);
  return res.json();
}

async function post(action, body = {}) {
  const res = await fetch(`${BASE}?action=${action}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body),
  });
  if (!res.ok) throw new Error(`API error ${res.status}`);
  return res.json();
}

export const api = {
  home:       ()                  => get('home'),
  settings:   ()                  => get('settings'),
  categories: ()                  => get('categories'),
  projects:   (params = {})       => get('projects', params),
  project:    (slug)              => get('project', { slug }),
  articles:   (params = {})       => get('articles', params),
  article:    (slug)              => get('article',  { slug }),
  pilar:      (name)              => get('pilar',    { name }),
  about:      ()                  => get('about'),
  contact:    (body)              => post('contact',  body),
};
