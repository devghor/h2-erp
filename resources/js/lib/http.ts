function xsrfToken(): string {
    const match = document.cookie.match(/(?:^|; )XSRF-TOKEN=([^;]*)/);
    return match ? decodeURIComponent(match[1]) : '';
}

export async function http<T = unknown>(
    method: 'GET' | 'POST' | 'PATCH',
    url: string,
    params?: Record<string, string | number>,
): Promise<T> {
    const target = params
        ? `${url}${url.includes('?') ? '&' : '?'}${new URLSearchParams(Object.entries(params).map(([k, v]) => [k, String(v)]))}`
        : url;
    const response = await fetch(target, {
        method,
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-XSRF-TOKEN': xsrfToken(),
        },
    });
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return (await response.json().catch(() => undefined)) as T;
}
