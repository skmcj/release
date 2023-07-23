import request from '@/utils/request';

/**
 * 获取当前ip天气
 */
export const getWeatherApi = function () {
  return request.get<Weather>('https://api.vvhan.com/api/weather');
};

export interface Weather {
  city: string;
  info: {
    date: string;
    week: string;
    type: string;
    low: string;
    high: string;
    fengxiang: string;
    fengli: string;
    night: {
      type: string;
      fengxiang: string;
      fengli: string;
    };
    air: {
      aqi: number;
      aqi_level: number;
      aqi_name: string;
      co: string;
      no2: string;
      o3: string;
      pm10: string;
      'pm2.5': string;
      so2: string;
    };
    tip: string;
  };
}
