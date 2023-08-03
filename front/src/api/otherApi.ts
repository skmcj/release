import request from '@/utils/request';

/**
 * 获取当前ip天气
 */
export const getWeatherApi = function () {
  return request.get<Weather>('https://api.vvhan.com/api/weather');
};

/**
 * 获取星座运势
 */
export const getHoroscopeApi = function (ss: string = 'pisces') {
  return request.get<Horoscope>(`https://api.vvhan.com/api/horoscope?type=${ss}&time=today`);
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

export interface Horoscope {
  data: {
    title: string;
    type: string;
    time: string;
    todo: {
      yi: string;
      ji: string;
    };
    fortune: {
      all: number;
      love: number;
      work: number;
      money: number;
      health: number;
    };
    index: {
      all: string;
      love: string;
      work: string;
      money: string;
      health: string;
    };
    shortcomment: string;
    fortunetext: {
      all: string;
      love: string;
      work: string;
      money: string;
      health: string;
    };
    luckynumber: string;
    luckycolor: string;
    luckyconstellation: string;
  };
}
